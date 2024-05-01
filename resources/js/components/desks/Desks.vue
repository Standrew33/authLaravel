<template>
    <div class="container">
        <h1>Desks</h1>
        <form @submit.prevent="addNewDesk">
            <div class="form-group">
                <input type="text" v-model="name" class="form-control" placeholder="Enter name the desk" :class="{ 'is-invalid': v$.name.$error }">
                <div class="invalid-feedback" v-if="v$.name.required.$invalid">
                    Required field
                </div>
                <div class="invalid-feedback" v-if="v$.name.maxLength.$invalid">
                    Max amount symbols: {{ v$.name.maxLength.$params.max }}
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add</button>
        </form>
        <div class="alert alert-danger mt-3" role="alert" v-if="errored">
            Data load error! <br>
            {{ errors[0] }}
        </div>
        <div class="row">
            <div class="col-lg-4" v-for="desk in desks">
                <div class="card mt-3">
                    <router-link class="card-body" :to="{name: 'showDesk', params: { deskId: desk.id }}">
                        <h4 class="card-title">{{ desk.name }}</h4>
                    </router-link>
                    <button class="btn btn-danger mt-3" @click="deleteDesk(desk.id)">Delete</button>
                </div>
            </div>
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
     setup () {
         return { v$: useVuelidate() }
     },
    data() {
        return {
            desks: [],
            errored: false,
            errors: [],
            loading: true,
            name: null
        }
    },
    mounted() {
        this.getAllDesks()
    },
    methods: {
        getAllDesks() {
            axios.get('/api/V1/desks/')
                .then(response => {
                    this.desks = response.data.data
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        deleteDesk(id) {
            if (confirm('Are you sure want delete desk?')) {
                axios.post('/api/V1/desks/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.desks = []
                        this.getAllDesks()
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
        addNewDesk() {
            this.v$.$touch()
            if (this.v$.$invalid) {
                return;
            }
            axios.post('/api/V1/desks/', {
                name: this.name
            })
                .then(response => {
                    this.name = ''
                    this.desks = []
                    this.getAllDesks()
                })
                .catch(error => {
                    console.log(error)
                    if (error.response.data.errors.name) {
                        this.errors = []
                        this.errors.push(error.response.data.errors.name[0])
                    }
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        }
    },
    validations() {
        return {
            name: {
                required,
                maxLength: maxLength(255)
            }
        }
    }
}
</script>
