<template>
  <Modal :header="header" :confirmHeader="confirmHeader" :modalType="'confirm'" :paragraphs="paragraphs">
    <div class="flex justify-between w-full mt-10 sm:flex-col sm:flex-col-reverse">
      <!-- <button
        class="h-14 sm:w-full rounded border-2 border-orange text-base leading-4 text-orange"
        :class="{ 'w-48': buttons[0].length > 11, 'px-8': buttons[0].length < 12 }"
        @click="$emit('confirmStatus',{param: true})"
      >
        {{ buttons[0] }}
      </button>
      <button
        class="h-14 sm:w-full rounded bg-orange text-base leading-4 text-white mb-4"
        :class="{ 'w-48': buttons[1].length > 11, 'px-8': buttons[1].length < 12 }"
        @click="$emit('confirmStatus', {param: false})"
      >
        {{ buttons[1] }}
      </button> -->
      <!-- <div class="flex mb-4">
            <input
                type="checkbox"
                name="selall"
                id="selall"
                value="selall"
                class="w-6 h-6 border border-[#8A9CC9] rounded mr-4 accent-orange"

            />
            <label for="selall">
                <span class="font-bold text-sm leading-6">
                Todos los usuarios
                </span>
            </label>
        </div> -->
        <div class="flex mb-6 flex-col w-full" >

            <div class="flex flex-row mb-4" v-for="(info, index) in infoProyectos" :key="index">
              <div class="basis-1/3">
              <input
                    type="radio"
                    value="true"

                    v-model="info.confirmar"

                /> &nbsp;
                <span class="text-sm" >Aceptar</span>
                <br>

                <input
                    type="radio"
                    value="false"

                    v-model="info.confirmar"

                /> &nbsp;
                <span class="text-sm" >Rechazar</span>
              </div>
              <div class="basis-2/3"> El usuario {{info.mail}}, te ha invitado a ser parte del proyecto {{info.proyecto}} </div>



                <!-- <input type="radio" id="one" value="One" v-model="picked">
                <label for="one">One</label>
                    <br>
                    <input type="radio" id="two" value="Two" v-model="picked">
                <label for="two">Two</label>
                <label>
                  <span class="font-medium text-sm leading-6">
                      El Usuario juan@medina te ah invitado a unirte al proyecto, INVERSIONES SAC
                  </span>
                </label> -->


            </div>

          <br>
          <button
              class="h-14  sm:w-full rounded border-2 border-orange text-base leading-4 text-orange"
              @click="$emit('confirmChanges', infoProyectos)"
            >
              Confirmar Cambios
          </button>

        </div>

    </div>
  </Modal>
</template>

<script>
import Modal from "../components/Modal.vue";

export default {
  name: "success-component",
  props: {
    header: String,
    confirmHeader: String,
    paragraphs: Array,
    // buttons: Array,
    val: String,
    infoProyectos: {
            type: Array,
            default: []
        },
  },
  components: {
    Modal,
  },
  data: function () {
    return {
      data_guadar : []
    }
  }
};
</script>
