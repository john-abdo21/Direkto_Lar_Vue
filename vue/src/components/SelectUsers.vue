<template>
    <Modal :header="'Selecciona Integrantes'" :modalType="'selectusers'" :status="status">
        <div class="flex mb-4">
            <input
                type="checkbox"
                name="selall"
                id="selall"
                value="selall"
                class="w-6 h-6 border border-[#8A9CC9] rounded mr-4 accent-orange"
                @change="selectAll"
            />
            <label for="selall">
                <span class="font-bold text-sm leading-6">
                Todos los usuarios
                </span>
            </label>
        </div>
        <div class="flex mb-6 flex-col w-full" v-for="(user, index) in modelValue" :key="index">
            <div class="flex mb-4">
                <input
                    type="checkbox"
                    :name="'user_'+index"
                    :id="'user_'+index"
                    :value="user.codProyIntegrante"
                    class="w-6 h-6 border border-[#8A9CC9] rounded mr-4 accent-orange"
                    v-model="this.$store.state.restriction_rows_real[rowIndex].integrantes"
                    @change="capturamosCambios"

                />
                <label :for="'user_'+index">
                    <span class="font-medium text-sm leading-6">
                    {{user.desCorreo}}
                    </span>
                </label>
            </div>
        </div>



    </Modal>
  </template>

  <script>
  import Modal from "./Modal.vue";

  export default {
    name: "add-person-component",
    components: {
        Modal,
    },
    props: {
        modelValue: {
            type: Array,
            default: []
        },
        rowId: Number,
        rowIndex: Number
    },
    data: function () {
        return {
            status: false,
            // selUsers: this.$store.state.restriction,
            selAllState: false
        };
    },
    methods: {
        capturamosCambios: function(payload) {



          let data_update = this.$store.state.restriction_rows_real[this.rowIndex].integrantes
          this.$emit('capturamosCambios', {lista: data_update, codProyecto: this.$store.state.restriction_rows_real[this.rowIndex].codProyecto});

        },
        updSelectAll: function(){

          this.selAllState = this.$store.state.restriction_rows_real[this.rowIndex].integrantes.length != this.$store.state.restriction_rows_real[this.rowIndex].integrantesProy.length ? false : true

        },
        selectAll: function() {
            this.selAllState = !this.selAllState
            if(this.selAllState === false) {

                this.$store.state.restriction_rows_real[this.rowIndex].integrantes = []
            }
            else {

              let pintegrantes = this.$store.state.restriction_rows_real[this.rowIndex].integrantesProy
              let integrantes  = []
              pintegrantes.forEach (inte => {

                integrantes.push(inte.codProyIntegrante);

              });

              this.$store.state.restriction_rows_real[this.rowIndex].integrantes = integrantes;

            }

            this.capturamosCambios()
        }
    },
    mounted: function() {

      // this.updSelectAll()

    }
  };
  </script>
