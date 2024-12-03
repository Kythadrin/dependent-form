<style>
    .loader {
        border-width: 2px;
        border-style: solid;
        border-radius: 50%;
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Register</h1>
            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name:</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                    />
                    <div v-for="nameError in errors.name" :key="nameError">
                        <p class="text-sm text-red-500 mt-2">{{ nameError }}</p>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="text"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                    />
                    <div v-for="emailError in errors.email" :key="emailError">
                        <p class="text-sm text-red-500 mt-2">{{ emailError }}</p>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password:</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                    />
                    <div v-for="passwordError in errors.password" :key="passwordError">
                        <p class="text-sm text-red-500 mt-2">{{ passwordError }}</p>
                    </div>
                </div>

                <div>
                    <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password:</label>
                    <input
                        id="passwordConfirmation"
                        v-model="form.passwordConfirmation"
                        type="password"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                    />
                    <div v-for="passwordConfirmationError in errors.passwordConfirmation" :key="passwordConfirmationError">
                        <p class="text-sm text-red-500 mt-2">{{ passwordConfirmationError }}</p>
                    </div>
                </div>

                <div class="relative">
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country:</label>
                    <div class="relative">
                        <select
                            id="country"
                            v-model="form.country"
                            @change="fetchLanguages"
                            :disabled="loadingCountries"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        >
                            <option value="" disabled>Select a country</option>
                            <option v-for="country in countries" :key="country" :value="country">
                                {{ country }}
                            </option>
                        </select>
                        <div v-if="loadingCountries" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="loader w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                    <div v-for="countryError in errors.country" :key="countryError">
                        <p class="text-sm text-red-500 mt-2">{{ countryError }}</p>
                    </div>
                </div>

                <div class="relative">
                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Language:</label>
                    <div class="relative">
                        <select
                            id="language"
                            v-model="form.language"
                            :disabled="!languages.length || loadingLanguages"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                            :class="{ 'bg-gray-200': !languages.length || loadingLanguages }"
                        >
                            <option value="" disabled>Select a language</option>
                            <option v-for="language in languages" :key="language">
                                {{ language }}
                            </option>
                        </select>
                        <div v-if="loadingLanguages" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="loader w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                    <div v-for="languageError in errors.language" :key="languageError">
                        <p class="text-sm text-red-500 mt-2">{{ languageError }}</p>
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition flex items-center justify-center"
                >
                    <span v-if="loading" class="loader w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
                    <span>{{ loading ? "Submitting..." : "Register" }}</span>
                </button>
            </form>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            countries: [],
            languages: [],
            form: {
                name: "",
                email: "",
                password: "",
                passwordConfirmation: "",
                country: "",
                language: "",
            },
            errors: {
                name: [],
                email: [],
                password: [],
                passwordConfirmation: [],
                country: [],
                language: [],
            },
            loadingCountries: false,
            loadingLanguages: false,
            loading: false,
        };
    },
    methods: {
        async fetchCountries() {
            this.loadingCountries = true;
            try {
                const response = await axios.get("/country");
                this.countries = response.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.loadingCountries = false;
            }
        },
        async fetchLanguages() {
            if (!this.form.country) return;

            this.loadingLanguages = true;
            try {
                const response = await axios.get(`/language/${this.form.country}`);
                this.languages = response.data;
                this.errors.language = null;
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error(error);
                }
            } finally {
                this.loadingLanguages = false;
            }
        },
        validateForm() {
            if(!this.form.name) {
                this.errors.name.push("Name required");
            }
            if(!this.form.email) {
                this.errors.email.push("Email required");
            } else if (this.validEmail(this.form.email)) {
                this.errors.email.push("Email not valid");
            }
            if(!this.form.password) {
                this.errors.password.push("Password required");
            } else if (this.form.password.length < 8) {
                this.errors.password.push("The password field must be at least 8 characters.");
            }
            if(!this.form.passwordConfirmation) {
                this.errors.passwordConfirmation.push("Confirm password required");
            }
            if (this.form.password !== this.form.passwordConfirmation) {
                this.errors.passwordConfirmation.push("Passwords do not match");
            }
            if (!this.form.country) {
                this.errors.country.push("Country required");
            }
            if (!this.form.language) {
                this.errors.language.push("Language required");
            }
        },
        validEmail: function (email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return !regex.test(email);
        },
        hasErrors: function () {
            return Object.values(this.errors).some(errorArray => errorArray.length > 0)
        },
        async submitForm() {
            this.errors = {
                name: [],
                email: [],
                password: [],
                passwordConfirmation: [],
                country: [],
                language: [],
            }

            this.validateForm();

            if (!this.hasErrors()) {
                this.loading = true;
                try {
                    const response = await axios.post("/register", this.form);
                    console.log("Form submitted successfully:", response.data);
                } catch (error) {
                    if (error.response?.data?.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error(error);
                    }
                } finally {
                    this.loading = false;
                }
            }
        },
    },
    mounted() {
        this.fetchCountries();
    },
};
</script>
