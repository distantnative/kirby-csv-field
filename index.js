(function(){"use strict";function i(e,t,s,c,d,f,p,m){var o=typeof e=="function"?e.options:e;return t&&(o.render=t,o.staticRenderFns=s,o._compiled=!0),{exports:e,options:o}}const n={extends:"k-files-field",props:{columns:Object},data(){return{rows:null}},computed:{buttonsModified(){return this.file?[{autofocus:this.autofocus,text:this.$t("remove"),icon:"remove",responsive:!0,click:()=>this.remove(0)}]:this.buttons},columnsResolved(){return this.columns?Object.fromEntries(Object.keys(this.columns).map(e=>[this.columns[e].key??e,this.columns[e]])):this.rows?Object.fromEntries(Object.keys(this.rows[0]).map(e=>[e,{label:e}])):[]},file(){return this.value[0]}},watch:{file:{async handler(e){this.rows=null,e&&(this.rows=await this.$api.get(this.endpoints.field+"/preview",{file:e.id}))},immediate:!0}}};var l=function(){var t=this,s=t._self._c;return s("k-field",t._b({staticClass:"k-models-field k-csv-field",scopedSlots:t._u([t.disabled?null:{key:"options",fn:function(){return[s("k-button-group",{ref:"buttons",staticClass:"k-field-options",attrs:{buttons:t.buttonsModified,layout:"collapsed",size:"xs",variant:"filled"}})]},proxy:!0}],null,!0)},"k-field",t.$props,!1),[s("k-dropzone",{attrs:{disabled:!t.hasDropzone},on:{drop:t.drop}},[t.rows?s("k-table",{attrs:{columns:t.columnsResolved,rows:t.rows}}):t.file?s("k-empty",{attrs:{text:"Loading table…",icon:"loader",layout:"table"}}):s("k-empty",{attrs:{icon:"table",layout:"table",text:"Select or upload CSV file"},on:{click:t.open}})],1)],1)},r=[],a=i(n,l,r);const u=a.exports;panel.plugin("distantnative/kirby-csv-field",{fields:{csv:u}})})();
