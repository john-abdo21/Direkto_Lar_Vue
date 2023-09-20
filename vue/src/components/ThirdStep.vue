<template>
  <div class="flex flex-col w-[80%] m-auto">
    <span class="text-base sm:text-xl mb-4"
      >Configura la frecuencia con la que se enviará el reporte del proyecto</span
    >

    <template v-if="reportstate === true">
      <div class="w-[80%] sm:w-full">
        <div class="flex sm:flex-col justify-between">
          <span class="text-xs sm:mr-0 sm:mb-2 sm:w-full">Días de programación</span>
          <Select
            class="text-[0.7rem]"
            :indexVal="1"
            :typeVal="'text'"
            :placeHolder="'Selecciona'"
            :selType="'pro_days'"
            :class="'w-[50%] sm:w-full'"
            @selTypeFre="selTypeFre"
            :value="proDay"
            :options="programmingDayTypes"
          />
        </div>
        <div class="flex mb-4 sm:mb-2 items-center">
          <input
            type="checkbox"
            name="quality_date"
            id="quality_date"
            value="quality_date"
            class="w-4 h-4 border border-[#8A9CC9] text-[0.7rem] rounded mr-4 accent-orange"
          />
          <div>
            <label for="quality_date" class="text-[0.7rem] leading-6 mb-1"
              >Fecha de calidad</label
            >
            <div class="flex">
              <span class="mr-1 font-medium text-xs leading-5 text-[0.7rem]">
                *Opción válida para
                <span class="font-semibold text-[#002B6B]">PREMIUM</span>
              </span>
              <img src="../assets/premium.svg" alt="" />
            </div>
          </div>
        </div>
        <div>
          <div v-for="(report, index) in reports" :key="index">
            <div class="flex sm:flex-col justify-between sm:mb-4">
              <span class="text-[0.7rem] sm:mr-0 sm:w-full sm:mb-2">Reporte a programar</span>
              <!-- Report a programming -->
              <Select
                :indexVal="index"
                :typeVal="'text'"
                :placeHolder="'Reporte'"
                :selType="'report'"
                :class="'w-[50%] sm:w-full text-[0.7rem]'"
                @selReport="selReport"
                :value="report.reportSchedule"
                :options="[
                  {
                    value: 'Reporte de análisis de restricciones',
                    name: 'Reporte de análisis de restricciones',
                  },
                  {
                    value: 'Reporte de avance gráfico',
                    name: 'Reporte de avance gráfico',
                  },
                ]"
              />
            </div>
            <!-- Envío de reporte masivo check box -- start -->
            <div class="flex sm:flex-col justify-between items-center">
              <div class="flex sm:mb-2 sm:w-full items-center">
                <input
                  type="checkbox"
                  :name="'massive_all_' + index"
                  :id="'massive_all_' + index"
                  :value="'massive_all_' + index"
                  @input="massiveAll(index)"
                  v-model="report.massiveStatus"
                  class="w-4 h-4 border border-[#8A9CC9] rounded mr-4 accent-orange"
                />
                <label :for="'massive_all_' + index" class="text-[0.7rem] leading-6"
                  >Envío de reporte masivo</label
                >
              </div>
            </div>
            <!-- Envío de reporte masivo check box -- end -->

            <!-- report massive status is false, then display -- start -->
            <div class="mt-6" :class="report.reportSchedule === '' ? 'hidden' : ''">
              <div
                v-for="(user, childIndex) in report.frequencies"
                :key="childIndex"
                :class="report.massiveStatus === true ? 'hidden' : ''"
              >
                <div class="flex mb-6" v-if="childIndex === 0">
                  <input
                    type="checkbox"
                    :name="'apply_all_' + index"
                    :id="'apply_all_' + index"
                    :value="'apply_all_' + index"
                    v-model="report.applyAllStatus"
                    @input="applyAll(index)"
                    class="w-4 h-4 border border-[#8A9CC9] rounded mr-4 accent-orange"
                  />
                  <label :for="'apply_all_' + index" class="text-[0.7rem] leading-6"
                    >Aplicar frecuencia para todos los usuarios de este reporte</label
                  >
                </div>
                <!-- select based on the report apply all status -- start -->
                <div
                  class="flex sm:flex-col justify-between mb-6 items-center"
                  :class="report.applyAllStatus && childIndex > 0 ? 'hidden' : ''"
                >
                  <span class="text-[0.7rem] sm:mb-2 sm:w-full">{{
                    report.applyAllStatus ? "" : user.user
                  }}</span>
                  <Select
                    :indexVal="childIndex"
                    :typeVal="'text'"
                    :textState="report.applyAllStatus"
                    :placeHolder="'Frecuencia'"
                    :selType="'frequency'"
                    :class="'w-[50%] sm:w-full'"
                    :parentindex="index"
                    @selFrequency="selFrequency"
                    :value="frequencytext[parseInt(user.freq) - 1]"
                    :options="[
                      { value: 1, name: 'Diario' },
                      { value: 2, name: 'Interdiario' },
                      { value: 3, name: 'Semanal' },
                      { value: 4, name: 'Quincenal' }
                    ]"
                  />
                </div>
                <!-- select based on the report apply all status -- end -->
              </div>
              <!-- report massive status is false, then display -- end -->

              <!-- report massive status is true, then display -- start -->
              <div
                v-for="(user, childIndex) in users"
                :key="childIndex"
                class="flex sm:flex-col justify-between mb-6 items-center"
                :class="report.massiveStatus === false ? 'hidden' : ''"
              >
                <span class="text-base sm:mb-2 sm:w-full"></span>
                <Select
                  v-if="childIndex === 0"
                  :indexVal="childIndex"
                  :typeVal="'text'"
                  :placeHolder="'Frecuencia'"
                  :selType="'frequency'"
                  :class="'w-[50%] sm:w-[50%]'"
                  :value="frequencytext[parseInt(report.frequency) - 1]"
                  :parentindex="index"
                  @selFrequency="selFrequency"
                  :options="[
                    { value: 1, name: 'Diario' },
                    { value: 2, name: 'Interdiario' },
                    { value: 3, name: 'Semanal' },
                    { value: 4, name: 'Quincenal' }
                  ]"
                  :textState="report.massiveStatus"
                />
              </div>
              <!-- report massive status is true, then display -- end -->
            </div>
          </div>
        </div>
        <span
          class="font-medium text-xs leading-5"
          :class="paraStatus !== true ? 'hidden' : ''"
          >*Para enviar reporte por participante, debes desactivar el envío masivo y tener
          una cuenta PREMIUM
        </span>
        <div class="flex cursor-pointer mt-8 mb-8" @click="addReport">
          <img
            src="../assets/tooltip-person-add-active.svg"
            class="mr-2"
            alt=""
          />
          <span class="text-[0.7rem] leading-4 text-orange">Agregar nuevo reporte</span>
        </div>
      </div>
    </template>

    <template v-else>
      <div class="w-[80%] sm:w-full">
        <div class="flex sm:flex-col justify-between">
          <span class="text-base sm:mr-0 sm:mb-2 sm:w-full">Días de programación</span>
          <Select
            class="text-[0.7rem]"
            :indexVal="1"
            :typeVal="'text'"
            :placeHolder="'Selecciona'"
            :selType="'pro_days'"
            :class="'w-[50%] sm:w-full'"
            @selTypeFre="selTypeFre"
            :options="programmingDayTypes"
          />
        </div>
        <div class="flex mb-4 sm:mb-8">
          <input
            type="checkbox"
            name="quality_date"
            id="quality_date"
            value="quality_date"
            class="w-3 h-3 border border-[#8A9CC9] rounded mr-4 accent-orange"
          />
          <div>
            <label for="quality_date" class="text-sm leading-6 mb-1"
              >Fecha de calidad</label
            >
            <div class="flex">
              <span class="mr-1 font-medium text-xs leading-5 text-[#8A9CC9]">
                *Opción válida para
                <span class="font-semibold text-[#002B6B]">PREMIUM</span>
              </span>
              <img src="../assets/premium.svg" alt="" />
            </div>
          </div>
        </div>
        <div>
          <div v-for="(report, index) in reports" :key="index">
            <div class="flex sm:flex-col justify-between  sm:mb-4">
              <span class="text-[0.7rem] sm:mr-0 sm:w-full sm:mb-2">Reporte a programar</span>
              <Select
                :indexVal="index"
                :typeVal="'text'"
                :placeHolder="'Reporte'"
                :selType="'report'"
                :class="'w-[50%] sm:w-full'"
                @selReport="selReport"
                :options="[
                  {
                    value: 'Reporte de análisis de restricciones',
                    name: 'Reporte de análisis de restricciones',
                  },
                  {
                    value: 'Reporte de avance gráfico',
                    name: 'Reporte de avance gráfico',
                  },
                ]"
              />
            </div>
            <div class="flex sm:flex-col justify-between items-center">
              <div class="flex sm:mb-2 sm:w-full">
                <input
                  type="checkbox"
                  :name="'massive_all_' + index"
                  :id="'massive_all_' + index"
                  :value="'massive_all_' + index"
                  @input="massiveAll(index)"
                  class="w-4 h-4 border border-[#8A9CC9] rounded mr-4 accent-orange"
                />
                <label :for="'massive_all_' + index" class="text-[0.7rem] leading-6"
                  >Envío de reporte masivo</label
                >
              </div>
            </div>
            <div class="mt-6" :class="report.reportSchedule === '' ? 'hidden' : ''">
              <!-- massive status is true, then not display project users -- start -->
              <div
                v-for="(user, childIndex) in users"
                :key="childIndex"
                :class="report.massiveStatus === true ? 'hidden' : ''"
              >
                <div class="flex mb-6" v-if="childIndex === 0">
                  <input
                    type="checkbox"
                    :name="'apply_all_' + index"
                    :id="'apply_all_' + index"
                    :value="'apply_all_' + index"
                    @input="applyAll(index)"
                    class="w-6 h-6 border border-[#8A9CC9] rounded mr-4 accent-orange"
                  />
                  <label :for="'apply_all_' + index" class="text-sm leading-6"
                    >Aplicar frecuencia para todos los usuarios de este reporte</label
                  >
                </div>
                <!-- select based on the report apply all status -- start -->
                <div
                  class="flex sm:flex-col justify-between mb-6 items-center"
                  :class="report.applyAllStatus && childIndex > 0 ? 'hidden' : ''"
                >
                  <span class="text-base sm:mb-2 sm:w-full">{{
                    report.applyAllStatus ? "" : user.userEmail
                  }}</span>
                  <Select
                    :indexVal="childIndex"
                    :typeVal="'text'"
                    :placeHolder="'Frecuencia'"
                    :selType="'frequency'"
                    :class="'w-[80%] sm:w-full'"
                    :parentindex="index"
                    @selFrequency="selFrequency"
                    :options="[
                      { value: 1, name: 'Diario' },
                      { value: 2, name: 'Interdiario' },
                      { value: 3, name: 'Semanal' },
                      { value: 4, name: 'Quincenal' }
                    ]"
                  />
                </div>
                <!-- select based on the report apply all status -- end -->
              </div>
              <!-- massive status is true, then not display project users -- end -->

              <!-- report massive status is true, then display -- start -->
              <div
                v-for="(user, childIndex) in users"
                :key="childIndex"
                class="flex sm:flex-col justify-between mb-6 items-center w-[50%]"
                :class="report.massiveStatus === false ? 'hidden' : ''"
              >
                <span class="text-base sm:mb-2 sm:w-full"></span>
                <Select
                  class="w-[50%]"
                  v-if="childIndex === 0"
                  :indexVal="childIndex"
                  :typeVal="'text'"
                  :placeHolder="'Frecuencia'"
                  :selType="'frequency'"
                  :class="'sm:w-full'"
                  :parentindex="index"
                  @selFrequency="selFrequency"
                  :options="[
                    { value: 1, name: 'Diario' },
                    { value: 2, name: 'Interdiario' },
                    { value: 3, name: 'Semanal' },
                    { value: 4, name: 'Quincenal' }
                  ]"
                />
              </div>
              <!-- report massive status is true, then dilay -- end -->
            </div>
          </div>
        </div>
        <span
          class="font-medium text-xs leading-5"
          :class="paraStatus !== true ? 'hidden' : ''"
          >*Para enviar reporte por participante, debes desactivar el envío masivo y tener
          una cuenta PREMIUM
        </span>
        <div class="flex cursor-pointer mt-8 mb-8" @click="addReport">
          <img
            src="../assets/tooltip-person-add-active.svg"
            class="mr-2"
            alt=""
          />
          <span class="text-base leading-4 text-orange">Agregar nuevo reporte</span>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import Select from "./Select.vue";
import { mapState, mapGetters } from "vuex";
export default {
  name: "third-step-component",
  components: {
    Select,
  },
  computed: {
    ...mapState({
      currentprojectreport: "currentprojectreport",
    }),
    ...mapGetters({
      proDay: "proDay",
      reportstate: "reportstate",
    }),
  },
  data: function () {
    return {
      paraStatus: true,
      users: [],
      reports: this.$store.state.currentprojectreport,
      TypeFrequency: "",
      reporttext: ["Reporte de análisis de restricciones", "Reporte de avance gráfico"],
      frequencytext: ["Diairo", "Interdiairo", "Semanal", "Quincenal"],

      /* lista tipos dia programacion - programming day type list */
      programmingDayTypes: [],
      programmingDayTypeCode: 0,
    };
  },

  methods: {
    addReport: function () {
      this.users = this.$store.state.projectUsers;
      var temp = {
        reportSchedule: "",
        frequencies: [],
        applyAllStatus: false,
        frequency: null,
        massiveStatus: false,
        usercreation: "",
      };
      if (this.reportstate) {
        this.users.forEach((user) =>
          temp.frequencies.push({ user: user.userEmail, freq: 0 })
        );
      }
      this.reports.push(temp);
      console.log(this.reports);
    },
    selTypeFre: function (payload) {
      this.TypeFrequency = this.programmingDayTypes.find(
        (t) => t.value === payload.value
      ).typeFrequency;
      this.programmingDayTypeCode = payload.value;
      /* update reports */
      this.reports.forEach((r) => {
        r.proDayCode = this.programmingDayTypeCode;
      });
      this.paraStatus = false;
    },
    selReport: function (payload) {
      this.reports[payload.indexVal].reportSchedule = payload.value;
      this.paraStatus = false;
    },
    selFrequency: function (payload) {
      var frequencytemp = {
        user: this.users[payload.indexVal].userEmail,
        freq: payload.value,
      };
      if (
        this.reports[payload.parentindex].massiveStatus === true ||
        this.reports[payload.parentindex].applyAllStatus === true
      ) {
        this.users.forEach((user) => {
          this.reports[payload.parentindex].usercreation += user.userEmail + ", ";
        });
        this.reports[payload.parentindex].frequency = payload.value;
        this.reports[payload.parentindex].frequencies[payload.indexVal] = frequencytemp;
      } else if (this.users[0].userEmail) {
        this.reports[payload.parentindex].frequency = payload.value;
        this.reports[payload.parentindex].frequencies[payload.indexVal] = frequencytemp;
        this.reports[payload.parentindex].usercreation += frequencytemp.user + ", ";
      }
      this.paraStatus = false;
    },
    applyAll: function (indexVal) {
      this.reports[indexVal].applyAllStatus = !this.reports[indexVal].applyAllStatus;
    },
    massiveAll: function (indexVal) {
      this.reports[indexVal].massiveStatus = !this.reports[indexVal].massiveStatus;
    },
  },
};
</script>
