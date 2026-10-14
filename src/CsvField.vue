<template>
	<k-field
		v-bind="$props"
		:class="['k-models-field', 'k-csv-field', $attrs.class]"
		:input="false"
		:style="$attrs.style"
	>
		<template v-if="!disabled" #options>
			<k-button-group
				ref="buttons"
				:buttons="file ? button : buttons"
				layout="collapsed"
				size="xs"
				variant="filled"
				class="k-field-options"
			/>
		</template>

		<!-- Table preview -->
		<k-table v-if="csv" v-bind="table" @paginate="preview($event.page)" />

		<!-- Loading state -->
		<k-empty
			v-else-if="file"
			:text="$t('field.csv.loading')"
			icon="loader"
			layout="table"
		/>

		<!-- Empty state -->
		<k-dropzone v-else :disabled="!hasDropzone" @drop="drop">
			<k-empty
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
	extends: "k-filepicker-field",
	data() {
		return {
			csv: null,
		};
	},
	computed: {
		button() {
			return [
				{
					icon: "remove",
					responsive: true,
					size: "xs",
					text: this.$t("remove"),
					variant: "filled",
					click: () => this.remove(0),
				},
			];
		},
		file() {
			return this.value[0];
		},
		index() {
			if (this.csv?.pagination) {
				return (this.csv.pagination.page - 1) * this.csv.pagination.limit + 1;
			}

			return 1;
		},
		pagination() {
			if (this.csv?.pagination) {
				return { ...this.csv.pagination, details: true };
			}

			return false;
		},
		table() {
			return {
				columns: this.csv.columns,
				index: this.index,
				pagination: this.pagination,
				rows: this.csv.rows,
			};
		},
	},
	watch: {
		file: {
			handler() {
				this.csv = null;
				this.preview();
			},
			immediate: true,
		},
	},
	methods: {
		async preview(page = 1) {
			if (!this.file) {
				return;
			}

			this.csv = await this.$panel.api.get(this.endpoints.field + "/preview", {
				file: this.file,
				page,
			});
		},
	},
};
</script>
