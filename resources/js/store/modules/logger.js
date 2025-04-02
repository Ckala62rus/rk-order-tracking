const state = {
    users: null,
    detailFormLoad: false,
    aggregateFormLoad: false,
    aggregateFormModalLoad: false,
    exportExcelAggregateForm: false,
}

export const mutationTypes = {
    users: '[logger] users',
    detailFormLoad: '[logger] detailFormLoad',
    aggregateFormLoad: '[logger] aggregateFormLoad',
    aggregateFormModalLoad: '[logger] aggregateFormModalLoad',
    exportExcelAggregateForm: '[logger] exportExcelAggregateForm',
}

export const actionTypes = {
    users: '[logger] users',
    detailFormLoad: '[logger] detailFormLoad',
    aggregateFormLoad: '[logger] aggregateFormLoad',
    aggregateFormModalLoad: '[logger] aggregateFormModalLoad',
    exportExcelAggregateForm: '[logger] exportExcelAggregateForm',
}

export const gettersTypes = {
    users: '[logger] users',
    detailFormLoad: '[logger] detailFormLoad',
    aggregateFormLoad: '[logger] aggregateFormLoad',
    aggregateFormModalLoad: '[logger] aggregateFormModalLoad',
    exportExcelAggregateForm: '[logger] exportExcelAggregateForm',
}

const getters = {
    [gettersTypes.users]: (state) => state.users,
    [gettersTypes.detailFormLoad]: (state) => state.detailFormLoad,
    [gettersTypes.aggregateFormLoad]: (state) => state.aggregateFormLoad,
    [gettersTypes.aggregateFormModalLoad]: (state) => state.aggregateFormModalLoad,
    [gettersTypes.exportExcelAggregateForm]: (state) => state.exportExcelAggregateForm,
}

const mutations = {
    [mutationTypes.users]: (state, users) => state.users = users,
    [mutationTypes.detailFormLoad]: (state, flag) => state.detailFormLoad = flag,
    [mutationTypes.aggregateFormLoad]: (state, flag) => state.aggregateFormLoad = flag,
    [mutationTypes.aggregateFormModalLoad]: (state, flag) => state.aggregateFormModalLoad = flag,
    [mutationTypes.exportExcelAggregateForm]: (state, flag) => state.exportExcelAggregateForm = flag,
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
        return new Promise((resolve) => {
            state.commit(mutationTypes.detailFormLoad, flag)
            resolve()
        })
    },
    [actionTypes.aggregateFormLoad](state, flag) {
        return new Promise((resolve) => {
            state.commit(mutationTypes.aggregateFormLoad, flag)
            resolve()
        })
    },
    [actionTypes.aggregateFormModalLoad](state, flag) {
        return new Promise((resolve) => {
            state.commit(mutationTypes.aggregateFormModalLoad, flag)
            resolve()
        })
    },
    [actionTypes.exportExcelAggregateForm](state, flag) {
        return new Promise((resolve) => {
            state.commit(mutationTypes.exportExcelAggregateForm, flag)
            resolve()
        })
    },
}

export default {
    state,
    mutations,
    actions,
    getters
}
