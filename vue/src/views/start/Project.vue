<template>
 <br><br>
 
    <div
        v-if="isLoading == false"
        class="h-full flex justify-center sm:items-start"
      >
        <loading
          v-model:active="isLoadingTrue"
          :can-cancel="false"
          :is-full-page="true"
          loader="dots"
        />


      </div>
    <div
    v-if="status > 0 && status < 5 && isLoading "
    id="create_project "

    :class="{ 'h-full flex justify-center sm:items-start': (projectLength == 0 && status == 4) }"
    >

      <div class="flex flex-col">
        <ProjectBar :step="status" />
        <Breadcrumb
          :paths="['Inicio', 'Crear Proyecto']"
          :urls ="['home']"
          :settingFlag="false"
          :class="status === 4 ? 'hidden' : 'm-1'"
        />
        <FirstStep :class="status === 1 ? '' : 'hidden'" ref="step1" />
        <SecondStep :class="status === 2 ? '' : 'hidden'" ref="step2" />
        <ThirdStep :class="status === 3 ? '' : 'hidden'" ref="step3" />
        <FooterStep
          :buttons="[
            'Cancelar',
            createstatus ? 'Crear proyecto' : 'Editar proyecto',
          ]"
          :flag="footerFlag"
          :text="'*Campos obligatorios'"
          @cancel="cancel"
          @next="nextStatus"
          :class="status === 4 ? 'hidden' : ''"
        />

        <div
          class="flex flex-col items-center w-[480px] sm:w-full mt-24 sm:mt-4 h-[312px] sm:h-[292px] p-16 sm:p-10 justify-center shadow-tooltip rounded-2xl bg-white cursor-pointer"
          :class="{ 'z-30': true }"
          @click="createNewProject"
          v-if="isLoading == true && projectLength == 0 && status === 4 "

        >
            <img
              src="../../assets/computer.svg"
              class="mb-8 sm:mb-6"
              alt=""
            />
            <span class="text-[28px] leading-9 text-center"
              >Crea tu primer proyecto</span
            >
        </div>

        <div class="flex flex-col" :class="status === 4 ? '' : 'hidden'" v-if="isLoading == true && projectLength > 0">
          <Breadcrumb
             :paths="['Inicio', 'Tus Proyectos']"
             :urls ="['home']"
             :settingFlag="false"
          />
          <Indicator
            :header="'Tus proyectos'"
            :paragraph="'Acá podrás visualizar tus proyectos creados y los datos más importantes. Podrás editarlo.'"
            :buttonText="'Crear proyecto'"
            @createNewProject="createNewProject"
          />
          <div class="w-10/12 mb-8 border sm:border-0 border-[#D0D9F1] rounded-lg m-auto">
            <DataTable
              :tableType="'common'"
              :cols="headerCols"
              :colsref= "headerColsRef"
              :rows="projectRows"
              class="sm:hidden"
            >
              <template #default="{ row, index }">
                <ProjectTableRow
                  :row="row"
                  :index="index"
                  @openModal="openModal"
                  @editProject="editProject"
                  @viewProject="viewProject"
                />
              </template>
            </DataTable>
            <div class="sm:flex flex-col justify-center hidden">
              <SquareBox v-for="(row, index) in projectRows" :key="index">
                <div class="flex mb-4 mt-2">
                  <span class="text-base">{{ row.projectName }}</span>
                </div>
                <div class="flex mb-2">
                  <span class="text-xs leading-5 mr-1"
                    >Estado:
                    <span class="font-medium">{{
                      row.data ? `Abierto` : `Cerrado`
                    }}</span></span
                  >
                </div>
                <div class="flex flex-col mb-2 text-xs leading-5">
                  <span>Equipo:</span>
                  <span
                    class="font-medium"
                    v-for="(equipment, index) in row.users"
                    :key="index"
                  >
                    {{ equipment }}
                  </span>
                </div>

                <div class="flex mb-2 justify-end text-orange text-sm leading-4">
                  <span class="mr-4">
                    <span
                      class="cursor-pointer"
                      @click="editProject(row.projectId)"
                      >Editar</span
                    >
                    &nbsp;| &nbsp;
                    <span
                      class="cursor-pointer"
                      @click="
                        viewProject(row.id);
                        openModal({ param: 'viewproject', id: row.id });
                      "
                      >Ver</span
                    >
                  </span>
                </div>
              </SquareBox>
            </div>
          </div>
          <!-- <AdvertisingBig :width="928" :height="100" />
          <Advertising /> -->
          <ViewProject
            v-if="modalName === 'viewproject'"
            @closeModal="closeModal"
            v-model="viewprojectData"
          />
          <EditStatus
            v-if="modalName === 'editStatus'"
            :estadoActual="estadoProjects"
            :header="'Cambiar Estado del proyecto'"
            :paragraphs="''"
            :subtitulo="subtituloModEstado"
            @closeModal="closeModal"
            @editStatusSave="editStatusSave"
          />
        </div>
      </div>
    </div>
</template>

<script>
import EditStatus from "../../components/EditStatus.vue";
import Hint from "../../components/Hint.vue";
import Confirm from "../../components/Confirm.vue";
import Success from "../../components/Success.vue";
import Breadcrumb from "../../components/Layout/Breadcrumb.vue";
import Indicator from "../../components/Layout/Indicator.vue";
import AdvertisingBig from "../../components/Layout/AdvertisingBig.vue";
import Advertising from "../../components/Layout/Advertising.vue";
import DataTable from "../../components/DataTable.vue";
import SquareBox from "../../components/SquareBox.vue";
import ProjectTableRow from "../../components/ProjectTableRow.vue";
import ProjectBar from "../../components/ProjectBar.vue";
import FirstStep from "../../components/FirstStep.vue";
import FooterStep from "../../components/FooterStep.vue";
import SecondStep from "../../components/SecondStep.vue";
import ThirdStep from "../../components/ThirdStep.vue";
import ViewProject from "../../components/ViewProject.vue";
import store from "../../store";
import { inject } from "vue";

import Loading from "vue-loading-overlay";
import axiosClient from "../../axios"

// import ClickOutside from "vue-click-outside";

export default {
  name: "project-view",
  components: {
    Loading,
    Hint,
    Confirm,
    Success,
    Breadcrumb,
    Indicator,
    AdvertisingBig,
    Advertising,
    DataTable,
    SquareBox,
    ProjectTableRow,
    ProjectBar,
    FirstStep,
    FooterStep,
    SecondStep,
    ThirdStep,
    ViewProject,
    EditStatus,
  },
  setup() {
    const emitter = inject("emitter");
    const onRegisterNotification = (value) => {
      emitter.emit("notificationRegistered", value);
    };
    return {
      onRegisterNotification,
    };
  },
  created() {
    this.subtituloModEstado = `

    <span class="text-sm leading-6 text-activeText font-medium sm:mb-4 ">* Si cierras el proyecto , analisis de restriccion tambien sera cerrado. </span> <br>
    <span class="text-sm leading-6 text-activeText font-medium sm:mb-4 ">* No se podra modificar el proyecto y solo se podras navegar en vista de lectura. </span>


    `;
  },
  data: function () {
    return {
      subtituloModEstado: null,
      notify: false,
      notifyMsg: 'This is test message',
      pageloadflag: false,
      viewprojectData: {},
      modalName: "",
      rowId: '',
      footerFlag: true,
      status: 0,
      headerColsRef: {

        project_name: "Nombre del proyecto",
        data: "Estado actual del proyecyo",
        type: "Tippo de proyecto",
        equipment: "Personas partes del equipo",
        action: " Acciones que se pueden realizar.",

      },
      headerCols: {
        project_name: "Proyecto",
        data: "Estado",
        type: "Tipo",
        equipment: "Equipo",
        action: "Acciones",
      },
      createstatus: true,
      projectId: null,
      notificationTypes: [
        {
          codNotificacion: 1,
          desNombre: "CreateProject",
          desDescripción: "Creacion de Proyecto",
          desPersonalizar: "Nada",
        },
        {
          codNotificacion: 2,
          desNombre: "CreateRestricction",
          desDescripción: "Creacion de restriccion",
          desPersonalizar: null,
        },
        {
          codNotificacion: 3,
          desNombre: "AssignRestriction",
          desDescripción: "Asignacion de restriccion",
          desPersonalizar: null,
        },
      ],
    };
  },
  methods: {

    valimosver() {

      console.log(this.estadoProjects)
    },
    handleRedirect(path) {
      this.$router.push(path);
    },

    cleanInputs: function () {

      this.$refs.step1.projectName = "";
      this.$refs.step1.business = "";
      this.$refs.step1.term = "";
      this.$refs.step1.coveredArea = "";
      this.$refs.step1.projectType = "";
      this.$refs.step1.startDate = "";
      this.$refs.step1.referenceAmount = "";
      this.$refs.step1.area = "";
      this.$refs.step1.builtArea = "";
      this.$refs.step1.country = "Peru";
      this.$refs.step1.address = "";
      this.$refs.step1.ubigeo = "";
      this.$refs.step1.codMoneda = "";

      this.$refs.step2.users = [];
      this.$refs.step2.areaIntegrantes = [];
      this.$refs.step2.rolIntegrantes = [];
      this.$refs.step3.reports = [];

      this.$refs.step1.searchText = "";
      this.$refs.step1.searchTextUbigeo = "";
      // this.$refs.step1.placeholder="Seleccionar Ubicación";
    },
    editStatusSave: function(payload) {


        let data  ={
          codProyecto : this.rowId,
          state       : payload.nuevoestado
        }

        console.log(">>>> antes de llegar")
        console.log(payload)
        console.log(data)

        store.dispatch('update_project_state', data).then(res => {
          console.log(">>>> imporesion de lo que devuelve")
          console.log(res)
          if(res.data.estado == true){

            this.$store.commit({
              type  : 'toggleEstadoProject',
              id    : data.codProyecto,
              estado: data.state
            });

            this.successHeader = "¡Estado actualizado con éxito!";
            this.openModal('success');

          }else{
            console.log("Tenemos errores al guardar")
          }

        });



    },
    openModal: function (param) {
      console.log(">>> param ")
      console.log(param)
      console.log(this.estadoProjects)
      if (typeof param !== "string") {
        this.rowId = param.id;
        param      = param.param;
      }
      this.modalName = param;
    },
    closeModal: function () {
      if (this.modalName === "") this.$store.commit("increaseHint");
      else this.modalName = "";
    },
    // confirmStatus: function (payload) {
    //   if (!payload.param) {
    //     this.$store.commit("copyRestriction");
    //     this.successHeader = "¡Proyecto agregado con éxito!";
    //     this.openModal("success");

    //     store.dispatch("get_project");
    //     this.$store.commit("copyRestriction");
    //     this.status = 4;
    //     this.$store.state.createStatus = true;
    //   } else {
    //     this.closeModal();
    //   }
    // },
    nextStatus: async function () {
      // console.log(this.status)
      if (this.createstatus == true) {
        this.status++;
        this.status > 1 && (this.footerFlag = false);
        this.$store.state.reportstate = false;
        switch (this.status) {
          case 2:

            if (this.$refs.step1.dataVerificamos() > 0) {
              // alert("please insert correct data and fill all fields.");
              this.status = 1;

            }
            break;
          case 3:
            if (!this.$refs.step2.validarMiembros() || this.$refs.step2.validarCampos() > 0){

               this.status = 2;
               break;

            }else{

              this.$store.state.projectUsers = this.$refs.step2.users;
              break;

            }


          case 4:
            const nowdate = new Date();
            const month = nowdate.getMonth() / 1 + 1;
            const savedate =
              nowdate.getFullYear() +
              "-" +
              month +
              "-" +
              nowdate.getDate() +
              " " +
              nowdate.getHours() +
              ":" +
              nowdate.getMinutes() +
              ":" +
              nowdate.getSeconds();
            var dayFechaInicio = new Date(this.$refs.step1.startDate);

            const projectData = {
              projectName: this.$refs.step1.projectName,
              business: this.$refs.step1.business,
              term: this.$refs.step1.term,
              coveredArea: this.$refs.step1.coveredArea,
              projectType: this.$refs.step1.projectType,
              district: this.$refs.step1.ubigeo,
              startDate: dayFechaInicio.toISOString().slice(0, 10),
              referenceAmount: this.$refs.step1.referenceAmount,
              area: this.$refs.step1.area,
              builtArea: this.$refs.step1.builtArea,
              country: this.$refs.step1.country,
              address: this.$refs.step1.address,
              date: savedate,
              id: null,
              userInvData: this.$refs.step2.users,
              reports: this.$refs.step3.reports,
              typeFrequency: this.$refs.step3.TypeFrequency,
              usersum: "",
              codMoneda: this.$refs.step1.codMoneda,
              /* code for programming day type */
              programmingDayTypeCode: this.$refs.step3.programmingDayTypeCode,
            };

            projectData.userInvData.forEach((user) => {
              projectData.usersum += user.userEmail + ", ";
            });


            this.pageloadflag = false
             await store.dispatch("create_project", projectData).then( async res => {

              this.pageloadflag = true

            });

            await this.cleanInputs();


        }

      } else {
        this.status++;
        this.$store.state.reportstate = true;
        switch (this.status) {
          case 2:
            console.log(">>>> entrandso aqui")
            if (this.$refs.step1.dataVerificamos() > 0) {
              // alert("please insert correct data and fill all fields.");
              this.status = 1;

            }
          break;

          case 3:

            if (!this.$refs.step2.validarMiembros() || this.$refs.step2.validarCampos() > 0){

              this.status = 1;
              break;

            }else{

              this.$store.state.projectUsers = this.$refs.step2.users;
              break;

            }


          case 4:
            const nowdate = new Date();
            const month = nowdate.getMonth() / 1 + 1;
            const savedate =
              nowdate.getFullYear() +
              "-" +
              month +
              "-" +
              nowdate.getDate() +
              " " +
              nowdate.getHours() +
              ":" +
              nowdate.getMinutes() +
              ":" +
              nowdate.getSeconds();
            var dayFechaInicio = new Date(this.$refs.step1.startDate);

            const newprojectData = {
              projectId: this.projectId,
              projectName: this.$refs.step1.projectName,
              business: this.$refs.step1.business,
              term: this.$refs.step1.term,
              coveredArea: this.$refs.step1.coveredArea,
              projectType: this.$refs.step1.projectType,
              codMoneda: this.$refs.step1.codMoneda,
              district: this.$refs.step1.ubigeo,
              startDate: dayFechaInicio.toISOString().slice(0, 10),
              referenceAmount: this.$refs.step1.referenceAmount,
              area: this.$refs.step1.area,
              builtArea: this.$refs.step1.builtArea,
              country: this.$refs.step1.country,
              address: this.$refs.step1.address,
              date: savedate,
              userInvData: this.$refs.step2.users,
              reports: this.$refs.step3.reports,
              typeFrequency: this.$refs.step3.TypeFrequency,
              usersum: "",
              codMoneda: this.$refs.step1.codMoneda,
              /* code for programming day type */
              programmingDayTypeCode: this.$refs.step3.programmingDayTypeCode,
            };
            newprojectData.userInvData.forEach((user) => {
              newprojectData.usersum += user.userEmail + ", ";
            });


            this.pageloadflag = false
            await store.dispatch("edit_project", newprojectData).then( async res => {

              // store.dispatch("get_project");
              // await this.cleanInputs();
              // await this.$store.commit("copyRestriction");
              this.pageloadflag = true

            });

            await this.cleanInputs();

        }
      }

    },
    editProject: function (payload) {
      /* El edit ocurre luego de que se hayan cargado las tablas de utilitarios*/
      store.dispatch("get_utilitarios").then((response) => {
        this.status = 1;
        this.createstatus = false;
        this.projectId = payload;
        const projects = this.$store.state.projects;
        const reports = [];

        this.$refs.step3.reports = [];
        this.$store.state.projectUsers = [];

        /* Llenamos las listas desplegables para el step 1*/

        this.$refs.step1.listaTiposproyectos = [];
        this.$refs.step1.listaUbigeos = [];
        this.$refs.step1.listaMonedas = [];

        this.$refs.step1.listaTiposproyectos = this.$store.state.tiposproyectos;
        this.$refs.step1.listaUbigeos = this.$store.state.ubigeos;
        this.$refs.step1.listaMonedas = this.$store.state.moneda;

        /* Llenamos las listas desplegables para el step 1*/

        /* Limpiamos y llenamos de nuevo las listas para el step 2*/
        const users = [];

        this.$refs.step2.areaIntegrantes = [];
        this.$refs.step2.rolIntegrantes = [];

        let tareaintegrante = this.$store.state.areaintegrante;
        for (let index = 0; index < tareaintegrante.length; index++) {
          this.$refs.step2.areaIntegrantes.push({
            value: tareaintegrante[index]["codArea"],
            name: tareaintegrante[index]["desArea"],
          });
        }

        let trolintegrante = this.$store.state.rolintegrante;
        for (let index = 0; index < trolintegrante.length; index++) {
          this.$refs.step2.rolIntegrantes.push({
            value: trolintegrante[index]["codRolIntegrante"],
            name: trolintegrante[index]["desRolIntegrante"],
          });
        }

        this.$refs.step3.programmingDayTypes = [];
        this.$refs.step3.programmingDayTypes = this.$store.state.programmingDayTypes;

        /* Limpiamos y llenamos de nuevo las listas para el step 2*/

        projects.forEach((pro) => {
          if (pro.codProyecto == payload) {
            var dayFechainicio = new Date(pro.dayFechaInicio);

            this.$refs.step1.projectName = pro.desNombreProyecto;
            this.$refs.step1.business = pro.desEmpresa;
            this.$refs.step1.searchText = pro.nombreEmpresa;
            this.$refs.step1.term = String(pro.numPlazo);
            this.$refs.step1.coveredArea = String(pro.numAreaTechado);
            this.$refs.step1.projectType = pro.codTipoProyecto;
            // this.$refs.step1.district=pro.codUbigeo;
            this.$refs.step1.startDate = dayFechainicio;
            this.$refs.step1.referenceAmount = String(pro.numMontoReferencial);
            this.$refs.step1.area = String(pro.numAreaTechada);
            this.$refs.step1.builtArea = String(pro.numAreaConstruida);
            this.$refs.step1.country = pro.desPais;
            this.$refs.step1.address = pro.desDireccion;
            this.$refs.step1.ubigeo = pro.codUbigeo;
            this.$refs.step1.searchTextUbigeo = pro.desUbigeo;
            this.$refs.step1.codMoneda = pro.codMoneda;
            // this.$refs.step1.placeholder = pro.desUbigeo;

            const invusers = pro.desUsuarioCreacion
              .substr(0, pro.desUsuarioCreacion.length - 1)
              .split(", ");
          }
        });

        store.dispatch("get_projectuser", payload).then(() => {
          const prousers = this.$store.state.currentprojectusers;
          /* Llenamos la lista de correos */
          prousers.forEach((user) => {
            const temp = {
              codProyIntegrante : user.codProyIntegrante,
              userEmail: user.desCorreo,
              userRole: user.codRolIntegrante,
              userArea: user.codArea,
              id: user.idIntegrante,
              flgInsertado: true,
              suggestiondata: [],
            };
            users.push(temp);
          });
          this.$refs.step2.users = users;
          /* Llenamos la lista de correos */
          this.$refs.step3.users = users;
        });

        store.dispatch("get_projectreport", payload).then(() => {
          this.$refs.step3.reports = this.$store.state.currentprojectreport;

          /* When editing a specific project, set step3 TypeFrequency from the current project report */
          if (this.$store.state.currentprojectreport[0]) {
            let proDayCode =
              this.$store.state.currentprojectreport[0].proDayCode;
            let matchObj = this.$refs.step3.programmingDayTypes.find(
              (obj) => obj.value === proDayCode
            );
            this.$refs.step3.TypeFrequency = matchObj
              ? matchObj.typeFrequency
              : "";
            this.$refs.step3.programmingDayTypeCode = matchObj ? proDayCode : 0;
          }
        });
      });
      // this.cleanInputs();


    },
    viewProject: function (payload) {
      const project = this.$store.state.projects[payload - 1];
      const projectInfo = {
        projectName: project.desNombreProyecto,
        business: project.desEmpresa,
        term: project.numPlazo,
        coveredArea: project.numAreaTechado,
        projectType: project.desTipoProyecto,
        district: project.codUbigeo,
        startDate: project.dayFechaInicio,
        referenceAmount: project.numMontoReferencial,
        area: project.numAreaTechada,
        builtArea: project.numAreaConstruida,
        country: project.desDireccion,
      };
      this.viewprojectData = projectInfo;
    },
    cancel: function () {
      this.status = 4;
      this.cleanInputs();
    },
    createNewProject: function () {
      this.createstatus = true;
      this.status       = 1;
      /* Despues de que obtenemos los datos de los utilitarios */
      store.dispatch("get_utilitarios").then((response) => {

        /* Llenamos las listas desplegables para el step 1*/
        this.$refs.step1.listaTiposproyectos = [];
        this.$refs.step1.listaUbigeos = [];
        this.$refs.step1.listaMonedas = [];

        this.$refs.step1.listaTiposproyectos = this.$store.state.tiposproyectos;
        this.$refs.step1.listaUbigeos = this.$store.state.ubigeos;
        this.$refs.step1.listaMonedas = this.$store.state.moneda;
        /* Llenamos las listas desplegables para el step 1*/

        /* Llenamos las listas desplegables para el step 2*/
        this.$refs.step2.areaIntegrantes = [];
        this.$refs.step2.rolIntegrantes = [];
        /* Lista del area del integrante */
        let tareaintegrante = this.$store.state.areaintegrante;
        for (let index = 0; index < tareaintegrante.length; index++) {
          this.$refs.step2.areaIntegrantes.push({
            value: tareaintegrante[index]["codArea"],
            name: tareaintegrante[index]["desArea"],
          });
        }
        /* Lista del rol del integrante */
        let trolintegrante = this.$store.state.rolintegrante;
        for (let index = 0; index < trolintegrante.length; index++) {
          this.$refs.step2.rolIntegrantes.push({
            value: trolintegrante[index]["codRolIntegrante"],
            name: trolintegrante[index]["desRolIntegrante"],
          });
        }

        this.$refs.step3.programmingDayTypes = [];
        this.$refs.step3.programmingDayTypes = this.$store.state.programmingDayTypes;

      });
    },
  },
  computed: {
    hint: function () {
      return this.$store.state.hint;
    },
    projectLength: function () {
      return this.$store.state.project_rows.length;
    },
    projectRows: function () {
      return this.$store.state.project_rows;
    },
    estadoProjects: function() {
       return this.$store.getters.statusProject(this.rowId);
    },
    // loadPage: function(){

    //    return this.pageloadflag
    // },
    isLoadingTrue() {
      return true;
    },
    isLoading: function () {
      return this.pageloadflag;
    },
  },

  mounted: async function () {
    await store.dispatch("get_infoPerson");
    await store.dispatch("get_project").then((response) => {
      console.log(">>> Entramos al iniciar")
      console.log(this.$store.state.project_rows)

    });

    // console.log(">>> al iniciar")
    // console.log(this.$store.state.project_rows)
    // state.project_rows
    // await this.$store.commit("copyRestriction");
    this.status       = 4;
    this.pageloadflag = true;

    // if (this.$store.state.createStatus === true) {
    //   await store.dispatch("get_project");
    //   // await this.$store.commit("copyRestriction");
    //   this.status = 4;
    //   // this.pageloadflag = "Se termino de cargar la pagina"
    // }



    // /* Get programming day type list */
    // await store.dispatch("get_programmingdaytypes").then((response) => {
    //   this.$refs.step3.programmingDayTypes = [];
    //   this.$refs.step3.programmingDayTypes =
    //     this.$store.state.programmingDayTypes;
    // });

  },
};
</script>
<style>
  .notify{
    width: fit-content;
    position: absolute;
    right: 100px;
    background: #008000e6;
    color: white;
    padding: 20px;
    border-radius: 5px;
    z-index: 9
  }

</style>
