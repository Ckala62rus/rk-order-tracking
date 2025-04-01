const state = {
    users: null,
    detailFormLoad: false,
    aggregateFormLoad: false,
}

export const mutationTypes = {
    users: '[logger] users',
    detailFormLoad: '[logger] detailFormLoad',
    aggregateFormLoad: '[logger] aggregateFormLoad',
}

export const actionTypes = {
    users: '[logger] users',
    detailFormLoad: '[logger] detailFormLoad',
    aggregateFormLoad: '[logger] aggregateFormLoad',
}

export const gettersTypes = {
    users: '[logger] users',
    detailFormLoad: '[logger] detailFormLoad',
    aggregateFormLoad: '[logger] aggregateFormLoad',
}

const getters = {
    [gettersTypes.users]: (state) => state.users,
    [gettersTypes.detailFormLoad]: (state) => state.detailFormLoad,
    [gettersTypes.aggregateFormLoad]: (state) => state.aggregateFormLoad,
}

const mutations = {
    [mutationTypes.users]: (state, users) => state.users = users,
    [mutationTypes.detailFormLoad]: (state, flag) => state.detailFormLoad = flag,
    [mutationTypes.aggregateFormLoad]: (state, flag) => state.aggregateFormLoad = flag,
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
    [actionTypes.detailFormLoad](state, flag) {
        return new Promise(async () => {
            state.commit(mutationTypes.detailFormLoad, flag)
        })
    },
    [actionTypes.aggregateFormLoad](state, flag) {
        return new Promise(async () => {
            state.commit(mutationTypes.aggregateFormLoad, flag)
        })
    },
}

export default {
    state,
    mutations,
    actions,
    getters
}
