<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
	products: Array,
});

const form = useForm({
	product_name: '',
	qty_in_stock: '',
	price: '',
});

const editingProduct = ref(null);

const editForm = useForm({
	product_name: '',
	qty_in_stock: '',
	price: '',
});

function submitForm() {
	form.post(route('products.store'), {
		onSuccess: () => form.reset(),
	});
}

/* Edit Product */
function setEdit(product) {
	editingProduct.value = product.id;
	editForm.product_name = product.product_name;
	editForm.qty_in_stock = product.qty_in_stock;
	editForm.price = product.price;
}

function submitEditForm(id) {
	editForm.put(route('products.update', id), {
		onSuccess: () => {
			editForm.reset();
			editingProduct.value = null;
		},
	});
}
</script>

<template>
	<div class="container mt-5">
		<form @submit.prevent="submitForm">
			<div class="mb-3">
				<label for="product_name" class="form-label">Product Name</label>
				<input v-model="form.product_name" class="form-control" id="product_name" />
			</div>

			<div class="mb-3">
				<label for="qty_in_stock" class="form-label">Quantity in Stock</label>
				<input type="number" v-model="form.qty_in_stock" class="form-control" id="qty_in_stock" />
			</div>

			<div class="mb-3">
				<label for="price" class="form-label">Price Per Item</label>
				<input type="number" v-model="form.price" class="form-control" id="price" />
			</div>

			<button type="submit" class="btn btn-primary">Submit</button>
		</form>

		<table class="table table-striped mt-5">
			<thead>
				<tr>
					<th>Product Name</th>
					<th>Quantity in Stock</th>
					<th>Price Per Item</th>
					<th>Date</th>
					<th>Total Value</th>
					<th>Edit</th>
				</tr>
			</thead>
			<tbody>
				<tr
					v-for="product in products.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))"
					:key="product.id">
					<template v-if="editingProduct !== product.id">
						<td>{{ product.product_name }}</td>
						<td>{{ product.qty_in_stock }}</td>
						<td>{{ product.price }}</td>
						<td>{{ product.created_at }}</td>
						<td>{{ product.qty_in_stock * product.price }}</td>
						<td><button class="btn btn-sm btn-warning" @click="setEdit(product)">Edit</button></td>
					</template>

					<template v-else>
						<td><input v-model="editForm.product_name" class="form-control" /></td>
						<td><input type="number" v-model="editForm.qty_in_stock" class="form-control" /></td>
						<td><input type="number" v-model="editForm.price" class="form-control" /></td>
						<td>{{ product.created_at }}</td>
						<td>{{ editForm.qty_in_stock * editForm.price }}</td>
						<td>
							<button class="btn btn-sm btn-success" @click="submitEditForm(product.id)">Save</button>
						</td>
					</template>
				</tr>

				<!-- SUM Total Row -->
				<tr class="fw-bold">
					<td colspan="4" class="text-end">Total:</td>
					<td>
						{{
							products.reduce((sum, product) => {
								return sum + product.qty_in_stock * product.price;
							}, 0)
						}}
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
