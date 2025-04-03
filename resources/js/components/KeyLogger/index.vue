<template>
    <div class="container-fluid">
        <div class="card card-custom rdp_statistic_mg">
            <div class="card-header">
                <h3 class="card-title">
                    Активность пользователей
                </h3>
            </div>

            <!--statistic table begin-->
            <div class="card-body">
                <el-tabs v-model="editableTabsValue" type="card">
                    <el-tab-pane
                        v-for="(item, index) in editableTabs"
                        :key="item.name"
                        :label="item.title"
                        :name="item.name"
                    >
                        <rk-detail-user-information v-if="item.name === '1'" />
                        <rk-agregate-user-information v-else-if="item.name === '2'" />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </div>
</template>

<script>

import {actionTypes} from "../../store/modules/logger";

import RkDetailUserInformation from './DetailUserInformation'
import RkAgregateUserInformation from './AgregateUserInformation'

export default {
    name: "index",

    components: {
        RkDetailUserInformation,
        RkAgregateUserInformation,
    },

    data() {
        return {
            editableTabsValue: '1',
            editableTabs: [
                { title: 'Общая форма', name: '1', content: 1 },
                { title: 'Сжатая форма', name: '2', content: 2 },
            ],
            tabIndex: 2,
        }
    },

    methods: {
        getLogins(){
            this.$store.dispatch(actionTypes.users)
        },
    },

    mounted() {
        this.getLogins()
    }
}
</script>

<style scoped>

.rdp_statistic_mg {
    margin-top: -20px;
    margin-bottom: 10px;
}

</style>
