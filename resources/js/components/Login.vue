<template>
    <div>
        <section class="bg-gray-50">
            <div
                class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
            >
                <div
                    class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0"
                >
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <h1
                            class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl"
                        >
                            Connectez-vous à votre compte
                        </h1>
                        <form class="space-y-4 md:space-y-6" action="javascript:void(0)" method="post">
                            <div class="col-12" v-if="Object.keys(validationErrors).length > 0">
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <label
                                    for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900"
                                    >Email</label
                                >
                                <input
                                    type="email"
                                    v-model="auth.email"
                                    name="email"
                                    id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                                    placeholder="name@company.com"
                                    required=""
                                />
                            </div>
                            <div>
                                <label
                                    for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900"
                                    >Mot de passe</label
                                >
                                <input
                                    type="password"
                                    v-model="auth.password"
                                    name="password"
                                    id="password"
                                    placeholder="••••••••"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                                    required=""
                                />
                            </div>
                            
                            <button
                                type="submit"
                                :disabled="processing" @click="login"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                            >
                                {{ processing ? "Connexion" : "Se connecter" }}
                            </button>
                            <p
                                class="text-sm font-light text-gray-500"
                            >
                                Pas encore de compte ?
                                <router-link :to="{name: 'register'}" class="font-medium text-blue-600 hover:underline">S'inscrire</router-link>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import { mapActions } from 'vuex';
import apiClient from '../api';

export default {
    name: "Login",
    data(){
        return {
            auth:{
                email:"",
                password:""
            },
            validationErrors:{},
            processing:false
        }
    },
    methods:{
        ...mapActions({
            signIn:'auth/login'
        }),
        async login(){
            this.processing = true
            await apiClient.get('/sanctum/csrf-cookie')
            await apiClient.post('/login',this.auth).then(({data})=>{
                this.signIn()
            }).catch(({response})=>{
                if(response.status === 422){
                    this.validationErrors = response.data.errors
                }else{
                    this.validationErrors = {}
                }
            }).finally(()=>{
                this.processing = false
            })
        },
    }
};
</script>
