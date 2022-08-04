import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        counter: 1000,
        deleteModalObj: {
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            deletingIndex: -1,
            isDeleted: false,
            msg: '',
            successmsg: ''
        },
        user: false,
        permission: null,
        photo: '',
        resData: ''
    },
    getters: {
        getDeleteModalObj(state) {
            return state.deleteModalObj
        },

        getCounter(state) {
            // if (state.counter > 1002) return 'O my God, this is too big'
            // return 'Ok that is small'
            return state.counter
        },
        getUserPermission(state) {
            return state.permission
        },
        getPhoto(state) {
            // if (state.counter > 1002) return 'O my God, this is too big'
            // return 'Ok that is small'
            return state.photo
        },
        getResData(state) {
            // if (state.counter > 1002) return 'O my God, this is too big'
            // return 'Ok that is small'
            return state.resData
        },
        // getCounterByHalf(state) {
        //     return state.counter / 2
        // }

    },
    mutations: {
        changeTheCounter(state, data) {
            //this.counter += data
            console.log(data)
            state.counter += data

        },
        setDeleteModal(state, data) {
            const deleteModalObj = {
                showDeleteModal: false,
                deleteUrl: '',
                data: null,
                deletingIndex: state.deleteModalObj.deletingIndex,
                isDeleted: data,
                msg: '',
                successmsg: ''
            }
            state.deleteModalObj = deleteModalObj
                //state.deleteModalObj = deleteModalObj.isDeleted
                // this.isDeleting =true

        },
        setResData(state, data) {
            state.resData = data
        },
        setPhoto(state, data) {
            state.photo = data
        },
        setDeletingModalObj(state, data) {
            state.deleteModalObj = data
        },
        setUpdateUser(state, data) {
            state.user = data
        },
        setUserPermission(state, data) {
            state.permission = data
        }
    },
    actions: {
        changeCounterAction({ commit }, data) {
            console.log(commit)
            commit('changeTheCounter', data)
                //state.counter += data
        }
    }
})