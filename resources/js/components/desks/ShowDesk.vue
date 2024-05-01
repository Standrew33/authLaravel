<template>
    <div class="container">
        <h1>{{ name }}</h1>
        <div class="form-group">
            <input type="text" @blur="saveName" v-model="name" class="form-control" :class="{ 'is-invalid': v$.name.$error }">
            <div class="invalid-feedback" v-if="v$.name.required.$invalid">
                Required field
            </div>
            <div class="invalid-feedback" v-if="v$.name.maxLength.$invalid">
                Max amount symbols: {{ v$.name.maxLength.$params.max }}
            </div>
        </div>
        <form @submit.prevent="addNewDeskList" class="mt-3">
            <div class="form-group">
                <input type="text" v-model="desk_list_name" class="form-control" placeholder="Enter name the desk list" :class="{ 'is-invalid': v$.desk_list_name.$error }">
                <div class="invalid-feedback" v-if="v$.desk_list_name.required.$invalid">
                    Required field
                </div>
                <div class="invalid-feedback" v-if="v$.desk_list_name.maxLength.$invalid">
                    Max amount symbols: {{ v$.desk_list_name.maxLength.$params.max }}
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add desk list</button>
        </form>
        <div class="row mt-3">
            <div class="col-lg-4" v-for="desk_list in desk_lists">
                <div class="card mt-3">
                    <div href="#" class="card-body">
                        <form @submit.prevent="updateDeskList(desk_list.id, desk_list.name)" v-if="desk_list_input_id == desk_list.id">
                            <input type="text" v-model="desk_list.name" class="form-control" placeholder="Enter name the desk list">
                            <button type="button" class="close" aria-label="Close" @click="desk_list_input_id = null"><span aria-hidden="true">&times;</span></button>
                        </form>
                        <h4 v-else class="card-title d-flex justify-content-between align-items-center" style="cursor: pointer;" @click="desk_list_input_id = desk_list.id">
                            {{ desk_list.name }}<i class="fas pencil-alt" style="font-size: 15px;"></i>
                        </h4>
                    </div>
                    <button class="btn btn-danger mt-3" @click="deleteDeskList(desk.id)">Delete</button>
                </div>
            </div>
        </div>
        <div class="alert alert-danger" role="alert" v-if="errored">
            Data load error!
        </div>
        <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status" v-if="loading">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>

<script>
import { required, maxLength } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
export default {
    props: [
        'deskId'
    ],
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            name: null,
            errored: false,
            loading: true,
            desk_lists: true,
            desk_list_name: null,
            desk_list_input_id: null
        }
    },
    methods: {
        getDeskLists() {
            axios.get('/api/V1/desk-lists', {
                params: {
                    desk_id: this.deskId
                }
            })
                .then(response => {
                    this.desk_lists = response.data.data
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        saveName() {
            this.v$.name.$touch()
            debugger;
            if (this.v$.$invalid) {
                return;
            }
            axios.post('/api/V1/desks/' + this.deskId, {
                _method: 'PUT',
                name: this.name
            })
                .then(response => {

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        addNewDeskList() {
            this.v$.$touch()
            if (this.v$.$invalid) {
                return;
            }
            axios.post('/api/V1/desk-lists', {
                name: this.desk_list_name,
                desk_id: this.deskId
            })
                .then(response => {
                    this.desk_list_name = ''
                    this.desk_lists = []
                    this.getDeskLists()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        deleteDeskList(id) {
            axios.post('/api/V1/desk-lists/' + id, {
                _method: 'DELETE'
            })
                .then(response => {
                    this.desk_lists = []
                    this.getDeskLists()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        updateDeskList(id, name) {
            axios.post('/api/V1/desk-lists/' + id, {
                _method: 'PUT',
                name
            })
                .then(response => {
                    this.desk_list_input_id = null
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        }
    },
    mounted() {
        axios.get('/api/V1/desks/' + this.deskId)
            .then(response => {
                this.name = response.data.data.name
            })
            .catch(error => {
                console.log(error)
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        this.getDeskLists()
    },
    validations() {
        return {
            name: {
                required,
                maxLength: maxLength(255)
            },
            desk_list_name: {
                required,
                maxLength: maxLength(255)
            },
        }
    }
}
</script>
