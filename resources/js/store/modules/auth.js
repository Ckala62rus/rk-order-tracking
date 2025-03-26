const state = {
    user: null,
    isSubmitting: false,
    isLoginSubmit: false,
    counter: 0,
    isAuth: false,
    authError: [],

}

export const mutationTypes = {
    me: '[auth] me',
}

export const actionTypes = {
    me: '[auth] me',
}

export const gettersTypes = {
    isAuth: '[auth] auth',
    user: '[auth] user',
}

const getters = {
    [gettersTypes.isAuth]: (state) => state.isAuth,
    [gettersTypes.user]: (state) => state.user
}

const mutations = {
    ['increase']: (state) => state.counter++,
    ['decrease']: (state) => state.counter--,
    [mutationTypes.me]: (state, user) => state.user = user,
}

const actions = {
    [actionTypes.me](state) {
        return new Promise(() => {
            console.log("action vuex work!")
        })
    },
}

export default {
    state,
    mutations,
    actions,
    getters
}
