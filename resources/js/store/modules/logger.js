const state = {
    users: null,
}

export const mutationTypes = {
    users: '[logger] users',
}

export const actionTypes = {
    users: '[logger] users',
}

export const gettersTypes = {
    users: '[logger] users',
}

const getters = {
    [gettersTypes.users]: (state) => state.users,
}

const mutations = {
    [mutationTypes.users]: (state, users) => state.users = users,
}

const actions = {
    [actionTypes.users](state) {
        return new Promise(async () => {
            await axios.get('/key-logger-logins' ).then((response) => {
                const users = response.data.users;
                state.commit(mutationTypes.users, users)
            })
        })
    },
}

export default {
    state,
    mutations,
    actions,
    getters
}
