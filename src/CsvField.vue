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
				:rows="rowsPaginated"
				:pagination="pagination"
				@paginate="page = $event.page"
			/>
			<k-empty
				v-else-if="file"
				text="Loading table…"
				icon="loader"
				layout="table"
			/>
			<k-empty
				v-else
				icon="table"
				layout="table"
				text="Select or upload CSV file"
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
			page: 1,
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
		pagination() {
			return {
				details: true,
				limit: this.limit,
				page: this.page,
				total: this.rows.length,
			};
		},
		rowsPaginated() {
			if (!this.limit) {
				return this.rows;
			}

			return this.rows.slice(
				(this.page - 1) * this.limit,
				this.page * this.limit,
			);
		},
	},
	watch: {
		file: {
			async handler(file) {
				this.rows = null;

				if (file) {
					this.rows = await this.$api.get(this.endpoints.field + "/preview", {
						file: file.id,
					});
				}
			},
			immediate: true,
		},
	},
};
</script>
