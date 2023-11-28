<x-app-layout>
    <div x-data="crudApp()" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 pt-0">
                <form @submit.prevent="addProduct" class="border-b border-gray-600 mb-6 mt-6 space-y-6 pb-6 md:w-1/3">
                    <p class="text-gray-500 dark:text-gray-400">Add new product</p>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input x-model="newProductName" id="name" type="text" required autofocus
                               class="mt-1 block w-full">
                        <p x-show="formErrors.name" x-text="formErrors.name" class="mt-2 text-sm text-red-600"></p>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" :disabled="isProcessing"
                                class="px-4 py-2 bg-blue-500 text-white text-xs font-semibold rounded hover:bg-blue-600">
                            Save
                        </button>
                        <span x-show="formSuccess" class="text-sm text-gray-600 dark:text-gray-400">Saved.</span>
                    </div>
                </form>

                <div class="rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">Product Name</th>
                            <th scope="col" class="py-3 px-6">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="p in products" :key="p.id">
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="py-4 px-6" x-text="p.name"></td>
                                <td>
                                    <button @click="deleteProduct(p.id)"
                                            class="px-4 py-2 bg-red-500 text-white text-xs font-semibold rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function crudApp() {
            return {
                products: @js($products),
                newProductName: '',
                formErrors: {},
                formSuccess: false,
                isProcessing: false,
                addProduct() {
                    this.isProcessing = true;
                    axios.post('/products', {name: this.newProductName})
                        .then(response => {
                            this.products.push(response.data);
                            this.newProductName = '';
                            this.formSuccess = true;
                            this.formErrors = {};
                            setTimeout(() => {
                                this.formSuccess = false;
                            }, 2000);
                        })
                        .catch(error => {
                            this.formErrors = error.response.data.errors;
                        })
                        .finally(() => {
                            this.isProcessing = false;
                        });
                },

                deleteProduct(productId) {
                    axios.delete('/products/' + productId)
                        .then(() => {
                            this.products = this.products.filter(product => product.id !== productId);
                        })
                        .catch(error => {
                            console.log('errr', error.response.data)
                        })
                        .finally(() => {
                            this.isProcessing = false;
                        });
                }
            };
        }
    </script>
</x-app-layout>
