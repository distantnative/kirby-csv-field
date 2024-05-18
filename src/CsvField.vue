<template>
  <k-field v-bind="$props" class="k-models-field k-csv-field">
    <template v-if="!disabled" #options>
      <k-button-group
        ref="buttons"
        :buttons="buttonsModified"
        layout="collapsed"
        size="xs"
        variant="filled"
        class="k-field-options"
      />
    </template>

    <k-dropzone :disabled="!hasDropzone" @drop="drop">
      <k-table v-if="rows" :columns="columnsResolved" :rows="rows" />
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
  },
  data() {
    return {
      rows: null,
    };
  },
  computed: {
    buttonsModified() {
      if (this.file) {
        return [
          {
            autofocus: this.autofocus,
            text: this.$t("remove"),
            icon: "remove",
            responsive: true,
            click: () => this.remove(0),
          },
        ];
      }

      return this.buttons;
    },
    columnsResolved() {
      if (this.columns) {
        return Object.fromEntries(
          Object.keys(this.columns).map((key) => [
            this.columns[key].key ?? key,
            this.columns[key],
          ])
        );
      }

      if (this.rows) {
        return Object.fromEntries(
          Object.keys(this.rows[0]).map((key) => [key, { label: key }])
        );
      }

      return [];
    },
    file() {
      return this.value[0];
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
