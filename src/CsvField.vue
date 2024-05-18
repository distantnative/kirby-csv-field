<template>
	<k-field v-bind="$props" class="k-models-field k-csv-field">
		<template v-if="!disabled" #options>
			<k-button
				v-if="file"
				:responsive="true"
				:text="$t('remove')"
				icon="remove"
				size="xs"
				variant="filled"
				@click="remove(0)"
			/>
			<k-button-group
				v-else
				ref="buttons"
				:buttons="buttons"
				layout="collapsed"
				size="xs"
				variant="filled"
				class="k-field-options"
			/>
		</template>

		<k-dropzone :disabled="!hasDropzone" @drop="drop">
			<k-table
				v-if="rows"
				:columns="cols"
				:index="index"
				:rows="rows"
				:pagination="pagination ? { ...pagination, details: true } : false"
				@paginate="preview(pagination.page + 1)"
			/>
			<k-empty
				v-else-if="file"
				:text="$t('field.csv.loading')"
				icon="loader"
				layout="table"
			/>
			<k-empty
				v-else
				:text="$t('field.csv.empty')"
				icon="table"
				layout="table"
				@click="open"
			/>
		</k-dropzone>
	</k-field>
</template>

<script>
export default {
	extends: "k-files-field",
	props: {
		columns: Object,
		limit: Number,
	},
	data() {
		return {
			rows: null,
			pagination: null,
		};
	},
	computed: {
		cols() {
			if (this.columns) {
				return Object.fromEntries(
					Object.keys(this.columns).map((key) => [
						this.columns[key].key ?? key,
						this.columns[key],
					]),
				);
			}

			if (this.rows) {
				return Object.fromEntries(
					Object.keys(this.rows[0]).map((key) => [key, { label: key }]),
				);
			}

			return [];
		},
		file() {
			return this.value[0];
		},
		index() {
			if (this.limit) {
				return (this.pagination.page - 1) * this.pagination.limit + 1;
			}

			return null;
		},
	},
	watch: {
		file: {
			async handler() {
				this.preview();
			},
			immediate: true,
		},
	},
	methods: {
		async preview(page = 1) {
			if (this.file) {
				const { rows, pagination } = await this.$api.get(
					this.endpoints.field + "/preview",
					{
						file: this.file.id,
						page,
					},
				);

				this.rows = rows;
				this.pagination = pagination;
			} else {
				this.rows = null;
			}
		},
	},
};
</script>
