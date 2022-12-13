const formControl = () => {
    const form = document.querySelector('form');
    const formReset = document.querySelector('.btn_reset');
    const input = document.getElementById('partNumber');
    const formSearch = document.querySelector('.search__form');
    const formResultContent = document.querySelector('.search_res');
    const formResultBlock = document.querySelector('.search__results');
    const returnSearchButton = document.querySelector('.return_search');
    const spinner = document.querySelector('.spinner');

    const showSpinner = () => {
        spinner.classList.toggle('hidden');
    }

    const hideOrShowSearchForm = () => {
        formSearch.classList.toggle('hidden');
        formResultBlock.classList.toggle('hidden');
    }

    returnSearchButton.addEventListener('click', () => {
        formResultContent.innerHTML = '';
        hideOrShowSearchForm();
    })

    formReset.addEventListener('click', () => {
        input.value = '';
    })

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        showSpinner();

        const formData = new FormData(form);
        const data = {}

        formData.forEach((value,key) => {
            data[key] = value;
        })

        if (data['code'].trim().length !== 0) {
            fetch('https://zm.leather.ru/tsd/api', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
            })
                .then(response => {
                    return response.json();
                })
                .then(res => {
                    if (res.error){
                        console.log(res.error);
                        formResultContent.textContent = res.error;
                        return;
                    }
                    // formResultContent.textContent = res.result;

                    res.result.forEach(elem => {
                        let p = document.createElement('p')
                        p.style.fontSize = '16px';
                        p.textContent = elem;
                        formResultContent.append(p)
                    })
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(() => {
                    hideOrShowSearchForm();
                    showSpinner();
                })
        }
    })
}

formControl();
// 3211@
