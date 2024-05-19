<template>
	<k-field v-bind="$props" class="k-models-field k-csv-field">
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
	extends: "k-files-field",
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
					click: () => this.clear(),
				},
			];
		},
		file() {
			return this.selected[0]?.id;
		},
		index() {
			if (this.csv?.pagination) {
				return (this.csv.pagination.page - 1) * this.csv.pagination.limit + 1;
			}
		},
		pagination() {
			return { ...(this.csv?.pagination ?? {}), details: true };
		},
		table() {
			if (this.csv) {
				return {
					...this.csv,
					index: this.index,
					pagination: this.pagination,
				};
			}

			return {};
		},
	},
	mounted() {
		this.preview();
	},
	methods: {
		clear() {
			this.selected = [];
			this.csv = null;
			this.onInput();
		},
		onInput() {
			this.$emit("input", this.selected);
			this.preview();
		},
		async preview(page = 1) {
			if (this.file) {
				this.csv = await this.$api.get(this.endpoints.field + "/preview", {
					file: this.file,
					page,
				});
			}
		},
	},
};
</script>
