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

		<k-dropzone :disabled="!hasDropzone" @drop="drop">
			<k-table v-if="rows" v-bind="table" @paginate="preview($event.page)" />
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
	},
	data() {
		return {
			rows: null,
			pagination: null,
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
		table() {
			return {
				columns: Object.fromEntries(
					Object.keys(this.columns ?? this.rows[0] ?? []).map((key) => [
						this.columns?.[key].key ?? key,
						this.columns?.[key] ?? { label: key },
					]),
				),
				index: this.pagination
					? (this.pagination.page - 1) * this.pagination.limit + 1
					: null,
				rows: this.rows,
				pagination: this.pagination
					? { ...this.pagination, details: true }
					: false,
			};
		},
	},
	mounted() {
		this.preview();
	},
	methods: {
		clear() {
			this.selected = [];
			this.rows = null;
			this.onInput();
		},
		onInput() {
			this.$emit("input", this.selected);
			this.preview();
		},
		async preview(page = 1) {
			if (this.file) {
				const { rows, pagination } = await this.$api.get(
					this.endpoints.field + "/preview",
					{
						file: this.file,
						page,
					},
				);

				this.rows = rows;
				this.pagination = pagination;
			}
		},
	},
};
</script>
