<template>
    <div>
        <section class="bg-gray-50">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0" >
                <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <h1
                            class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl"
                        >
                            Connectez-vous à votre compte
                        </h1>
                        <form class="space-y-4 md:space-y-6" action="javascript:void(0)" method="post">
                            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert" v-if="Object.keys(validationErrors).length > 0">
                                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <div>
                                    <span class="font-medium">{{ validationErrors.message }}</span>
                                    <ul class="mt-1.5 ml-4 list-disc list-inside" v-if="validationErrors.data">
                                        <li v-for="(value, key) in validationErrors.data" :key="key">{{ value[0] }}</li>
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
                                @click="login"
                                type="submit"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                            >
                                Se connecter
                            </button>
                            <p
                                class="text-sm font-light text-gray-500"
                            >
                                Pas encore de compte ? <router-link :to="{name: 'register'}" class="hover:underline hover:text-blue-500">Se créer un compte</router-link>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>

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
    methods: {
        
        async login() {
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/api/login', this.auth)
                .then((response) => {
                    console.log(response.data)
                    if(response.data.success){
                        this.$store.dispatch('login', {token: response.data.token, user: response.data.user});
                    }

                })
                .catch((error) => {
                    console.log({error})
                    this.validationErrors = {
                            message: error.response.data.message,
                            data: error.response.data.data,
                        }
                })
        }
    }
};
</script>
