import { forEach } from "lodash";
import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  state: {
    hint: 1,
    sidebar : false,
    menu: false,
    pivotID : -989,
    count: 1,
    reportstate: false,
    codPhaseTemp:0,
    // disabled_registers:false,
    project_rows: [],
    utilitarios:[],
    registerData: {
      firstName: '',
      lastName: '',
      phoneNumber: '',
      companyName: '',
      position: '',
      businessMail: '',
      plan: 'free',
      payType: '',
      cardInfo: {
        cardHolder: '',
        cardNumber: '',
        expireDate: '',
        cvc: '',
      }
    },
    restriction_rows_real: [{
      codAnaRes:1,
      codEstado:1,
      codProyecto:1,
      indNoRetrasados:0,
      indRetrasados:0,
      integrantes: [],
      integrantesProy: [
        {
          codProyIntegrante : 0,
          codProyecto       : 0,
          id                : 0,
          desCorreo         : ""
        },
        {
          codProyIntegrante : 1,
          codProyecto       : 1,
          id                : 1,
          desCorreo         : ""
        }
      ]
    }],
    restriction_rows: [
      {
        id: 1,
        data: true,
        projectName: "Proyecto Blanco",
        restriction: {
          delayed: 20,
          notDelayed: 55,
        },
        equipments: ["Lizeth Marzano"],
        users: [
          "lizeth.marzano-1@gmail.com",
          "sandra-gomez-1@gmail.com",
          "carla-gomez-1@gmail.com",
        ],
      },
      {
        id: 2,
        data: false,
        projectName: "Proyecto Negro",
        restriction: {
          delayed: 60,
          notDelayed: 40,
        },
        equipments: ["Lizeth Marzano", "Sandra Gomez"],
        users: [
          "lizeth.marzano-2@gmail.com",
          "sandra-gomez-2@gmail.com",
          "carla-gomez-2@gmail.com",
        ],
      },
    ],
    user: {
      data: {},
      name:  sessionStorage.getItem('Name'),
      token: sessionStorage.getItem("TOKEN"),
    },
    projects: [],
    codProyecto: '',
    projectUsers: [],
    currentprojectreport: [],
    currentprojectusers: [],
    selectedusers: [],
    whiteproject_rows:[],
    anaDataMembers:[],
    anaEstado:[],
    solicitanteActual:'',
    rolProyecto:0,
    areaUsuario:0,
    estadoRestriccion:false,
    createStatus: false,
    cargos:[],
    tiposproyectos:[],
    ubigeos:[
    {
      "codUbigeo":0,
      "desUbigeo":""
    },
    {
      "codUbigeo":1,
      "desUbigeo":""
    },

    {
      "codUbigeo":2,
      "desUbigeo":""
    },
    ],
    moneda:[],
    areaintegrante:[],
    rolintegrante:[],
    infoPerson:{
      img:'https://images.unsplash.com/photo-1597589022928-bb4002c099ec',
      data_save:{
        id:"",
        celular:"",
        name: "",
        email: "",
        lastname:"",
        nombreempresa:"",
        codcargo:0
      }
    },
    Restrictionlist:[],
    Restrictionlist_P:[],
    /* lista tipo dia programacion - programming day type list */
    programmingDayTypes: [],
    notifications: [],
    hiddenCols:[]
  },
  getters: {

    usersRestrictions: (state) => (id) => {
      const row = state.restriction_rows_real.find((row) => row.codProyecto === id);
      if (typeof row === "undefined") return [];
      return row.integrantes;
    },
    users: (state) => (id) => {
      const row = state.restriction_rows.find((row) => row.id === id);
      if (typeof row === "undefined") return [];
      return row.users;
    },
    addRestriction:(state) => (items) => {
      state.Restrictionlist = items;
    },
    addRestriction_P:(state) => (items) => {
      if(items === 0) {
        state.Restrictionlist_P = state.Restrictionlist_P;
      }
      state.Restrictionlist_P = items;
    },
    statusRestriction: (state) => (id) => {
      const row = state.restriction_rows_real.find((row) => row.codProyecto === id);
      if (typeof row === "undefined") return false;
      return row.codEstado;
    },
    statusProject: (state) => (id) => {
      console.log(">>>> rentrando")
      console.log(state.projects)
      console.log(id)
      const row = state.projects.find((row) => row.codProyecto === id);
      if (typeof row === "undefined") { return false; }
      else{
        console.log(">>>> entrmaos aqui ::"+String(row.codEstado))
        return row.codEstado;
      }

    },
    tableData: (state) => (payload) => {
      const row = state.whiteproject_rows.find((row) => row.id === payload.id);
      if (typeof row === "undefined") return [];
      const item = row.info.find((item) => item.id === payload.phaseId);
      if (typeof item === "undefined") return [];
      return item.tableData;
    },
    getWhiteprojectRows: (state) => {
      return state.whiteproject_rows;
    },
    // hideCols: (state) => (payload) => {
    //   const row = state.whiteproject_rows.find((row) => row.id === payload.id);
    //   if (typeof row === "undefined") return [];
    //   const item = row.info.find((item) => item.id === payload.phaseId);
    //   if (typeof item === "undefined") return [];
    //   return item.hideCols;
    // },
    reportstate: state => {
      return state.reportstate
    },
    proDay: state => {
      let currentObj;
      if (state.currentprojectreport[0]) {
        currentObj = state.programmingDayTypes.find(
          obj => obj.value === state.currentprojectreport[0].proDayCode
        );
      }
      return currentObj ? currentObj.name : '';
    },
    getResponsibleRows: state => payload => {
      return state.whiteproject_rows.map((row) => {
          return {
              ...row, listaFase: row.listaFase.map((fase) => {
                  return {
                      ...fase, listaRestricciones: fase.listaRestricciones.filter(restriction =>
                          restriction.idUsuarioResponsable === payload.value
                      )
                  }
              })
          };

      });
    },
    getApplicantRows: state => payload => {
      let applicantId = sessionStorage.getItem('Id');
      return state.whiteproject_rows.map((row) => {
          return {
              ...row, listaFase: row.listaFase.map((fase) => {
                  return {
                      ...fase, listaRestricciones: fase.listaRestricciones.filter(restriction =>
                          restriction.codUsuarioSolicitante === applicantId
                      )
                  }
              })
          };

      });
    },
    getExpirationRows: state => payload => {
      let res = state.whiteproject_rows.map((row) => {
          return {
              ...row, listaFase: row.listaFase.map((fase) => {
                  return {
                      ...fase, listaRestricciones: fase.listaRestricciones.filter(restriction =>
                          (restriction.codEstadoActividad !== state.anaEstado.find(estado => estado.desEstado === 'Completado').codEstado)
                          && (new Date(restriction.dayFechaRequerida) < new Date())
                      )
                  }
              })
          };

      });

      return res;
    },
    getResTypeRows: state => payload => {
      console.log(">>> entrando a filtrar")
      console.log(payload)
      return state.whiteproject_rows.map((row) => {
          return {
              ...row, listaFase: row.listaFase.map((fase) => {
                  return {
                      ...fase, listaRestricciones: fase.listaRestricciones.filter(restriction =>
                          restriction.codTipoRestriccion === payload.value
                      )
                  }
              })
          };

      });
    },

  },
  actions: {
    cleanListRestrictions(){
      this.state.whiteproject_rows = []
    },
    getUserName(){
      this.state.user.name = sessionStorage.getItem('Name')
      // commit('setUserName', sessionStorage.getItem('Name'))
    },
    getNameProy(){
      return sessionStorage.getItem('constraintNameProy')
      // commit('setUserName', sessionStorage.getItem('Name'))
    },
    get_utilitarios({commit}) {
      return axiosClient.get('/get_utilitarios_proyecto')
      .then(res => {
         commit('setUtilitarios', res.data)
      })
    },
    get_rolintegrante({commit}) {
      return axiosClient.get('/get_proyrolintegrante')
      .then(res => {
        commit('setRolIntegrante', res.data)
      })
    },
    get_areaintegrante({commit}) {
      return axiosClient.get('/get_areaintegrante')
      .then(res => {
        commit('setAreaIntegrante', res.data)
      })
    },
    get_moneda({commit}) {
      return axiosClient.get('/get_moneda')
      .then(res => {
        commit('setMoneda', res.data)
        // console.log(res);
        // return res.data
        // commit('setUser', res.data)
      })
    },
    get_ubigeos({commit}) {
      return axiosClient.get('/get_ubigeo')
      .then(res => {
        commit('setUbigeo', res.data)
        // console.log(res);
        // return res.data
        // commit('setUser', res.data)
      })
    },
    get_tipoproyectos({commit}) {
      return axiosClient.get('/get_tipoproyectos')
      .then(res => {
        commit('setTipoProyectos', res.data)
        // console.log(res);
        // return res.data
        // commit('setUser', res.data)
      })
    },
    get_cargos({commit}) {
      return axiosClient.get('/get_cargos')
      .then(res => {
        commit('setCargos', res.data)
        // console.log(res);
        // return res.data
        // commit('setUser', res.data)
      })
    },
    get_infoPerson({commit}) {

      console.log(">>> entrando aqui de esta forma ")
      console.log(this.state.user.data)

      if(Object.keys(this.state.user.data).length === 0){

        console.log(">>> llegamos aqui")

      const id = {
        id: sessionStorage.getItem('Id')
      }
      this.state.restriction_rows = []
      return axiosClient.post('/info_person', id)
      .then(res => {
        commit('setInfoPerson', res.data)
      })

      }else{

        return []
      }


    },

    register({commit}, user) {
      return axiosClient.post('/register', user)
        .then(({data}) => {
          /* Al generar el token y guardar el usuario estoy logueandolo automaticamente
             y se redirigira al Inicio
          */
          // commit('setUser', data.user);
          // commit('setToken', data.token);
          // console.log(data)
          return data;
        })
    },
    login({commit}, user) {
      return axiosClient.post('/login', user)
        .then(({data}) => {
          // console.log(data);
          commit('setUser', data.user);
          commit('setToken', data.token)
          commit('setFirstLogin', 1)
          return data;
        })
    },
    recoverPasswordSolicitude({commit}, user) {
      return axiosClient.post('/recuperar_credenciales_solicitud', user)
    },
    recoverPasswordValidate({commit}, user) {
      return axiosClient.post('/recuperar_credenciales_validar', user)
    },
    recoverPassword({commit}, user) {
      return axiosClient.post('/recuperar_credenciales', user)
    },
    create_project({commit}, projectData) {
      projectData.id = sessionStorage.getItem('Id');
      return axiosClient.post('/create_project', projectData)
      .then(res => {

          commit('setProject', res.data)
          res.data.forEach (pro => {
            commit('copyProjectFromDB', pro)
          });
      //   this.state.codProyecto = res.data.codPro
      //   return res.data
      })
    },
    register_notification({commit, state}, payload) {
      let p = {id: sessionStorage.getItem('Id'), date: payload.date, email: state.user.data.email};
      console.log(p);
      return axiosClient.post('/register_notification', p).then(res => {
          console.log(res.data);
          return res.data;
      })
    },
    edit_project({commit}, newprojectData) {
      newprojectData.id = sessionStorage.getItem('Id');
      return axiosClient.post('/edit_project', newprojectData)
      .then(res => {

          // this.state.restriction_rows = []
          commit('setProject', res.data)
          res.data.forEach (pro => {
            commit('copyProjectFromDB', pro)
          });
        // return res.data
      })
    },
    get_buscar({commit}, buscar ) {
      // newprojectData.id = sessionStorage.getItem('Id');
      return axiosClient.post('/get_buscar', buscar)
      .then(res => {
        console.log(res.data)
        return res.data
      })
    },
    get_buscar_usuarios({commit}, buscar ) {
      // newprojectData.id = sessionStorage.getItem('Id');
      return axiosClient.post('/get_search_person', buscar)
      .then(res => {
        console.log(res.data)
        return res.data
      })
    },
    save_newempresa({commit}, data ) {
      // newprojectData.id = sessionStorage.getItem('Id');
      return axiosClient.post('/set_new_empresa', data)
      .then(res => {
        console.log(res.data)
        return res.data
      })
    },
    logout({commit}) {
      // return axiosClient.post('/logout')
      //   .then(response => {
          commit('logout')
        //   return response;
        // })
    },
    getUser({commit}) {
      return axiosClient.get('/user')
      .then(res => {
        console.log(res);
        commit('setUser', res.data)
      })
    },
    get_restrictions({commit}) {
      const id = {
        id: sessionStorage.getItem('Id')
      }
      this.state.restriction_rows = []
      return axiosClient.post('/get_restrictions', id)
      .then(res => {
        commit('setRestrictionReal', res.data)
        // this.state.projects.forEach (pro => {
        //   commit('copyProjectFromDB', pro)
        // })
      })
    },
    get_project({commit}) {
      const id = {
        id: sessionStorage.getItem('Id')
      }
      // this.state.restriction_rows = []
      return axiosClient.post('/get_project', id)
      .then(res => {

          // console.log(">>>>> llegamos hasta aqui")
          // console.log(res)
          commit('setProject', res.data)
          res.data.forEach (pro => {
            commit('copyProjectFromDB', pro)
          })
      })
    },
    get_projectreport({commit}, projectId) {
      const projectreq = {
        projectId: projectId
      }
      return axiosClient.post('/get_projectreport', projectreq)
      .then(res => {
        commit('setCurrentReport', res.data)
      })
    },
    get_projectuser({commit}, projectId) {
      const projectreq = {
        projectId: projectId
      }
      return axiosClient.post('/get_projectuser', projectreq)
      .then(res => {
        //console.log(res.data)
        commit('setCurrentUsers', res.data)
      })
    },
    update_restricciones({commit}, restriction) {

      let restriction_row = {
        "codigo" : 1,
        "userId"    : sessionStorage.getItem('Id'),
        "projectId" : sessionStorage.getItem('constraintid'),
        "data"   :[]
      }
      restriction_row.data = restriction
      // arreglo.push(restriction_row)

      return axiosClient.post('/upd_restricciones', restriction_row)
    },
    update_numOrden({commit}, data) {

      let restriction_row = {
        "codigo"    :  1,
        // "userId"    : sessionStorage.getItem('Id'),
        "codProyecto" : sessionStorage.getItem('constraintid'),
        "data"   :[]
      }
      restriction_row.data = data
      // arreglo.push(restriction_row)

      return axiosClient.post('/upd_numOrden', restriction_row)
    },
    update_restriction_member({commit}, restriction) {
      const updateRes = {
        codProyecto : restriction.codProyecto,
        users       : restriction.lista,
      }

      return axiosClient.post('/update_restriction_member', updateRes);
    },
    update_restriction_state({commit}, dataUpdate) {
      // console.log(">>>> verificamos")
      // console.log(dataUpdate)
      const updateRes = {
        codProyecto : dataUpdate.codProyecto,
        state       : dataUpdate.state,
      }

      return axiosClient.post('/update_restriction_state', updateRes);
    },
    update_project_state({commit}, dataUpdate) {
      // console.log(">>>> verificamos")
      console.log(dataUpdate)
      const updateRes = {
        codProyecto : dataUpdate.codProyecto,
        state       : dataUpdate.state,
      }

      return axiosClient.post('/update_project_state', updateRes);
    },

    add_front({commit}, frontdata) {
      const nowdate = new Date();
      const month = nowdate.getMonth()/1+1;
      const savedate = nowdate.getFullYear()+'-'+month+'-'+nowdate.getDate()+
      ' '+ nowdate.getHours()+':'+nowdate.getMinutes()+':'+nowdate.getSeconds();
      const savedata = {
        name: frontdata.frontName,
        id: sessionStorage.getItem('constraintid'),
        date: savedate
      }
      return axiosClient.post('/add_front', savedata);
    },
    add_phase({commit}, phasedata) {
      const nowdate = new Date();
      const month = nowdate.getMonth()/1+1;
      const savedate = nowdate.getFullYear()+'-'+month+'-'+nowdate.getDate()+
      ' '+ nowdate.getHours()+':'+nowdate.getMinutes()+':'+nowdate.getSeconds();
      const savedata = {
        name: phasedata.phaseName,
        frontid: phasedata.frontId,
        projectid: sessionStorage.getItem('constraintid'),
        date: savedate
      }
      return axiosClient.post('/add_phase', savedata);
    },
    upd_infoPerson({commit}, dataPerson) {
      const enviar = {
        id:dataPerson.id,
        celular:dataPerson.celular,
        name: dataPerson.name,
        email: dataPerson.email,
        lastname:dataPerson.lastname,
        nombreempresa: dataPerson.nombreempresa,
        codcargo: dataPerson.codCargo
      };
      return axiosClient.post('/upd_person', enviar).then(res => {
        console.log(" >> vemos que impresion hace")
          console.log(res)
          if(res.data == 1){
             console.log(">> entrando a actualizar")
             commit('setUser', enviar)
          }

      });


    },
    del_Restrictions({commit}, data) {

      const send = {
        codAnaResActividad: data.codAnaResActividad
      }
      return axiosClient.post('/delete_restriction', send);
    },
    dup_Restrictions({commit}, codAnaResActividad) {

      const send = {
        codAnaResActividad: codAnaResActividad
      }
      return axiosClient.post('/duplicate_restriction', send);
    },
    Set_Restriction({commit}, data) {
      const num = data.length;
      const setData = {
        index: num,
        data: data
      }
      return axiosClient.post('/add_restriction', setData)
      .then(res => {
        commit('Set_Restriction', res.data)
      })
    },
    get_front({commit}){
      const anaresdata = { id: sessionStorage.getItem('constraintid') }
      // const anaresdata = 107;
      return axiosClient.post('get_front', anaresdata)
        .then(res => {
          console.log(res.data);
        commit('setAnaDataMembers', res.data)
      })
    },
    get_datos_restricciones({commit}){
      // let id = sessionStorage.getItem('Id');
      const anaresdata = { id: sessionStorage.getItem('constraintid') , codsuser: sessionStorage.getItem('Id') }
      // const anaresdata = 107;
      return axiosClient.post('get_data_restricciones', anaresdata)
        .then(res => {
          //console.log(res.data);
        commit('setAnaResData', res.data.restricciones)
        commit('Set_Restriction', res.data.tipoRestricciones)
        commit('setAreaIntegrante', res.data.areaIntegrante)
        commit('setAnaResDataMembers', res.data.integrantesAnaReS)
        commit('setEstado', res.data.estados)
        commit('setEstadoRestriccion', res.data.estadoRestriccion)
        commit('setSolicitanteActual', res.data.solicitanteActual)
        commit('setRolProyecto', res.data.rolUsuario)
        commit('setAreaUsuario', res.data.areaUsuario)

        if (!(res.data.columnasOcultas == null || res.data.columnasOcultas == '')){

          commit('setColOcultas', res.data.columnasOcultas)

        }
        return(res.data)
      })
    },
    report_restrictions_for_project({commit}){
      const data = { id: sessionStorage.getItem('constraintid') }
      // let params = { from: this.from, to: this.to };
      let paramString = new URLSearchParams(data);
      const url_reporte = import.meta.env.VITE_API_BASE_URL+`/api/generar_reporte?${paramString.toString()}`;
      window.open(url_reporte);


    },

    push_enviar_notificaciones({commit}){
      const anaresdata = { id: sessionStorage.getItem('constraintid') }
      // const anaresdata = 107;
      return axiosClient.post('push_enviar_notificaciones', anaresdata);

    },
    get_restriccionesMember({commit}){
      const anaresdata = { id: sessionStorage.getItem('constraintid') }
      // const anaresdata = 107;
      return axiosClient.post('get_restrictionsMember', anaresdata)
        .then(res => {
          console.log(res.data);
        commit('setAnaResDataMembers', res.data)
      })
    },
    get_restrictionStatus({commit}){
      return axiosClient.post('get_estado')
      .then(res => {
        commit('setEstado', res.data)

      })
    },
    delete_front({commit}, data){
      const anaresdata = { id: sessionStorage.getItem('constraintid') }
      const deleteData = {
        frontId : data.frontId,
        phaseId : data.phaseId,
        phaseLen : data.phaseLen,
        id: anaresdata
      }
      return axiosClient.post('delete_front', deleteData)
        .then(res => {
          console.log(res.data);
        commit('deleteFront', data)
      })
    },
    update_hidden_columns({commit}, data){
      //const anaresdata = { id: sessionStorage.getItem('constraintid') }
      const changeData = {
        codProyecto : sessionStorage.getItem('constraintid'),
        hidecolumns : data.hideCols.toString()
      }

      return axiosClient.post('update_hidden_columns', changeData);
    },
    get_Restriction_P({commit}){
      return axiosClient.get('get_restriction_p')
      .then(res => {
        commit('Set_Restriction', res.data)

      })
    },
    get_areaintegrante({commit}){
      return axiosClient.get('get_areaintegrante')
      .then(res => {
        commit('setAreaIntegrante', res.data)

      })
    },
    /* Action to get tipos dia programacion */
    // get_programmingdaytypes ({ commit }) {
    //   return axiosClient.get('get_programmingdaytypes').then(res => {
    //     commit('setProgrammingDayTypes', res.data)
    //   })
    // },

    /* Action to get applicants(solicitante) of the current project */
    get_proy_applicant({commit}, payload) {
      return axiosClient.post('get_proy_applicant', payload).then(res => {
          return res.data;
      });
  },


    get_resprojectuser({commit}, payload) {
      return axiosClient
          .post(
              '/get_projectuser',
              {projectId: payload.projectId},
              {params: {responsible: payload.responsible}}
          )
          .then(res => {
              console.log(res.data)
              return res.data
          })
    },
    get_projects_without_approve({commit}) {
      let id = sessionStorage.getItem('Id');
      return axiosClient
          .post('/projects_without_approve',{id: id});
          // .then(res => {
          //     console.log(res.data)
          //     return res.data
          // })
    },
    update_projects_without_approve({commit}, data) {

      let enviar = { data : data}

      return axiosClient.post('update_projects_without_approve', enviar)
    },
    get_notification({commit, state}) {
      let id = sessionStorage.getItem('Id');
      return axiosClient.post('get_notification', {id: id}).then(res => {
          commit('setNotification', res.data);
          return res.data;
      })
  },
    update_cod_notification({commit, state}, payload) {
        let cod_notification_usuario = payload.codNotificacionUsuario;
        return axiosClient.post('update_cod_notification', {cod_notification_usuario: cod_notification_usuario}).then(res => {
            return res.data;
        })
    }


  },
  mutations: {

    increment(state) {
      state.count++;
    },
    increaseHint(state) {
      state.hint++;
      state.menu = true;
      state.hint >= 4 && (state.menu = false);
    },
    toggleMenu(state) {
      state.menu = !state.menu;
    },
    toggleEstado(state, payload) {
      state.restriction_rows_real.find((row) => row.codProyecto === payload.id).codEstado = payload.estado
    },
    toggleEstadoProject(state, payload) {

      state.project_rows.find((row) => row.projectId === payload.id).codEstado = payload.estado
    },
    addUser(state, payload) {
      state.restriction_rows
        .find((row) => row.id === payload.id)
        .users.push(payload.email);
      return "ok";
    },
    editUser(state, payload) {
      state.restriction_rows.find((row) => row.id === payload.id).users =
        payload.users;
    },
    deleteUser(state, payload) {
      const users = state.restriction_rows.find(
        (row) => row.id === payload.id
      ).users;
      var ind;
      for (var i = 0; i < users.length; i++) {
        if (users[i] === payload.email) {
          ind = i;
        }
      }
      users.splice(ind, 1);
    },
    // setFilterColumn(state, payload) {
    //   var tempAry = ['date_required', 'responsible', 'responsible_area', 'condition', 'applicant'];
    //   var ind = tempAry.indexOf(payload.filterId);
    //   if (ind > -1) {
    //     tempAry.splice(ind, 1);
    //     const rows = state.whiteproject_rows;
    //     for (var i = 0; i < rows.length; i ++) {
    //       for (var j = 0; j < rows[i].info.length; j ++) {
    //         // console.log(rows[i].info[j])
    //         rows[i].info[j]['hideCols'] = tempAry;
    //       }
    //     }
    //   }
    // },
    setHideCols(state, payload) {
      state.whiteproject_rows
        .find((row) => row.id === payload.frontId)
        .info.find((item) => item.id === payload.phaseId).hideCols =
        payload.hideCols;
    },

    updPhase(state, payload) {
     let frente     = payload.frontId
     let faseTemp   = state.codPhaseTemp
     let faseReal   = payload.codFaseReal


     for (let i = 0; i < state.whiteproject_rows.length; i++) {
          if(state.whiteproject_rows[i]['codFrente'] == frente){
              for (let j = 0; j < state.whiteproject_rows[i]['listaFase'].length; j++) {
                  if( state.whiteproject_rows[i]['listaFase'][j]['codFase'] == faseTemp){

                      state.whiteproject_rows[i]['listaFase'][j]['codFase']    = faseReal;
                      delete  state.whiteproject_rows[i]['listaFase'][j]['insertar'];
                      state.codPhaseTemp = 0;
                      break;

                  }
              }
          }
     }


    },
    addFront(state, payload) {
      let codFaseTemp = 0
      if (payload.frontId === '') {
        const temp = {
          codFrente: payload.codFrenteReal,
          desFrente: payload.frontName,
          isOpen: true,
          listaFase: [],
        };
        state.whiteproject_rows.push(temp);
      } else {

        let id      = sessionStorage.getItem('Id')
        const row   = state.whiteproject_rows.find((row) => row.codFrente === payload.frontId);
        codFaseTemp = row.listaFase.length+1;
        const temp  = {
          codFase : codFaseTemp,
          insertar: 1,
          desFase : payload.phaseName,
          listaRestricciones: [],
          hideCols: [],
        };

        let newRows = []
        for (var i = 0; i < payload.cantNew; i ++) {

          let temp =   {
            codAnaResActividad: -1,//state.pivotID,
            codEstadoActividad: 1,
            codTipoRestriccion: "",
            dayFechaConciliada: "",
            dayFechaRequerida: "",
            dayFechaIdentificacion: "",
            desActividad: "",
            desAreaResponsable:"",
            desEstadoActividad:1,
            desRestriccion:"",
            desTipoRestriccion:"",
            desUsuarioResponsable:"",
            idUsuarioResponsable:"",
            isEnabled:true,
            isupdate:false,
            isEnabledFRequerida:true,
            isEnabledFConciliada:true,
            numOrden:0,

            dayFechaLevantamiento : "",
            codAreaRestriccion : payload.codAreaUsuario,
            desUsuarioSolicitante: "",
            idUsuarioSolicitante : "",

          }

          newRows.push(temp)
          state.pivotID = state.pivotID + 1

        }

        temp.listaRestricciones = newRows
        row.listaFase.push(temp);

      }
      state.codPhaseTemp = codFaseTemp

    },
    deleteFront(state, payload) {
      if (payload.phaseLen === 0 || payload.phaseId === '-999') {
        const rowIndex = state.whiteproject_rows.findIndex((row) => row.codFrente === payload.frontId);
        state.whiteproject_rows.splice(rowIndex, 1);
      } else {
        const row = state.whiteproject_rows.find((row) => row.codFrente === payload.frontId);
        const itemIndex = row.listaFase.findIndex((item) => item.codFase === payload.phaseId);
        row.listaFase.splice(itemIndex, 1);
      }
    },
    addScrollTableRow(state, payload) {
      let cantNew = payload.exercise
      let id      = sessionStorage.getItem('Id')
      let updRow   = []
      let updRow2  = []
      const row   = state.whiteproject_rows.find((row) => row.codFrente === payload.frontId).listaFase.find((item) => item.codFase === payload.phaseId);
      updRow      = row.listaRestricciones
      row.listaRestricciones = []


      // let conteo = 0
      updRow.forEach(function (item, key, mapObj) {
        row.listaRestricciones.push(item)
        if(item.codAnaResActividad == payload.restID){

          for (var i = 0; i < cantNew; i ++) {

              let temp =   {
                codAnaResActividad: state.pivotID,
                codEstadoActividad: 1,
                codTipoRestriccion: "",
                dayFechaConciliada: "",
                dayFechaRequerida: "",
                dayFechaIdentificacion: "",
                desActividad: "",
                desAreaResponsable:"",
                desEstadoActividad:1,
                desRestriccion:"",
                desTipoRestriccion:"",
                desUsuarioResponsable:"",
                idUsuarioResponsable:"",
                isEnabled:true,
                isupdate:false,
                numOrden:0,
                isEnabledFRequerida:true,
                isEnabledFConciliada:true,

                dayFechaLevantamiento : "",
                codAreaRestriccion : payload.codAreaUsuario,
                desUsuarioSolicitante: "",
                idUsuarioSolicitante : "",
              }

              row.listaRestricciones.push(temp)
              state.pivotID = state.pivotID + 1

          }

        }

        // conteo++;
      });



      // updRow2.push(temp)
      // row.listaRestricciones  = updRow2
      // updRow.push(temp);
      // row.listaRestricciones = updRow

      // for (var i = 0; i < cantNew; i ++) {

      //   const temp =   {
      //     codAnaResActividad: -999,
      //     codEstadoActividad: 1,
      //     codTipoRestriccion: "",
      //     dayFechaConciliada: "",
      //     dayFechaRequerida: "",
      //     desActividad: "",
      //     desAreaResponsable:"",
      //     desEstadoActividad:1,
      //     desRestriccion:"",
      //     desTipoRestriccion:"",
      //     desUsuarioResponsable:"",
      //     idUsuarioResponsable:id,
      //     isEnabled:false,
      //     isupdate:false,
      //     numOrden:0
      //   }

      //   row.listaRestricciones.push(temp);

      // }

    },
    saveRowfromForm(state, payload) {

      const rows = state.whiteproject_rows.find((row) => row.codFrente === payload.frontId).listaFase.find((item) => item.codFase === payload.phaseId).listaRestricciones;
      rows.push(payload.data.row)

    },
    delScrollTableRow(state, payload) {

      console.log(state.whiteproject_rows)
      const rows = state.whiteproject_rows.find((row) => row.codFrente === payload.frontId).listaFase.find((item) => item.codFase === payload.phaseId).listaRestricciones;

      var ind = '';
      for (var i = 0; i < rows.length; i ++) {
        if (rows[i]['codAnaResActividad'] === payload.activity) {
          ind = i;
        }
      }
      if (ind !== '')
        rows.splice(ind, 1);
    },
    updNotificaciones(state, payload) {

      state.whiteproject_rows.forEach(obj => {
        obj.listaFase.forEach(fase => {
          fase.listaRestricciones.forEach(restriccion => {
            // console.log(">>>>>>>")
            // console.log(restriccion)
            if (restriccion.flgNoti === 0) {
              // contador++;
              restriccion.flgNoti = 1;
            }
          });
        });
      });

    },
    duplicateScrollTableRow(state, payload) {
      console.log("<>>> entramos ")
      console.log(payload)
      const rows = state.whiteproject_rows.find((row) => row.codFrente === payload.frontId).listaFase.find((item) => item.codFase === payload.phaseId).listaRestricciones;
      var ind = '';

      for (var i = 0; i < rows.length; i ++) {
        if (rows[i]['codAnaResActividad'] == payload.activity) {
          ind = i;

          const regIns =   {
            codAnaResActividad: payload.codAna,
            codEstadoActividad: rows[i]['codEstadoActividad'],
            codTipoRestriccion: rows[i]['codTipoRestriccion'],
            dayFechaConciliada: rows[i]['dayFechaConciliada'],
            dayFechaRequerida:  rows[i]['dayFechaRequerida'],
            dayFechaIdentificacion:  payload.didentificacion,
            desActividad: rows[i]['desActividad'],
            desAreaResponsable: rows[i]['desAreaResponsable'],
            desEstadoActividad: rows[i]['desEstadoActividad'],
            desRestriccion: rows[i]['desRestriccion'],
            desTipoRestriccion: rows[i]['desTipoRestriccion'],
            desUsuarioResponsable: rows[i]['desUsuarioResponsable'],
            idUsuarioResponsable: rows[i]['idUsuarioResponsable'],
            numOrden : 0,
            isEnabled:true,
            isupdate:false,
            isEnabledFRequerida:true,
            isEnabledFConciliada:true,

            dayFechaLevantamiento : rows[i]['dayFechaLevantamiento'],
            codAreaRestriccion : payload.codAreaUsuario,
            desUsuarioSolicitante: rows[i]['desUsuarioSolicitante'],
            idUsuarioSolicitante : rows[i]['idUsuarioSolicitante'],

          }

          rows.push(regIns);

        }
      }

      // let ins = rows[ind]
      // reg[0]['codAnaResActividad'] = payload.codAna

      console.log(state.whiteproject_rows)
      // console.log(rows)
      // if (ind !== '')
      //   var ins = rows[ind]
      //   ins['codAnaResActividad'] = payload.codAna
      //   console.log(">>>> final a insertar")
      //   console.log(ins)

      //   console.log(">>>>>> lo final de final ")
      //   console.log(state.whiteproject_rows)
    },
    updateRegisterData(state, payload) {
      state.registerData[payload.attr] = payload.value;
    },
    updateRegisterDataCardInfo(state, payload) {
      state.registerData.cardInfo[payload.attr] = payload.value;
    },
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      sessionStorage.removeItem("TOKEN");
    },
    setFirstLogin: (state, valor) => {
      state.user.firstLogin = valor;
      sessionStorage.setItem('FirstLogin', valor);
    },

    setUser: (state, user) => {
      state.user.data = user;
      sessionStorage.setItem('Id', user.id);
      sessionStorage.setItem('Name', user.name+" "+user.lastname);
    },

    setToken: (state, token) => {
      state.user.token = token;
      sessionStorage.setItem('TOKEN', token);
    },
    setProject: (state, project) => {
      state.project_rows      = [];
      state.projects          = project;
    },
    setRestrictionReal: (state, restrictions) => {
      state.restriction_rows_real = restrictions;
    },
    setCargos: (state, cargos) => {
      state.cargos = cargos;
    },
    setTipoProyectos: (state, tiposproyectos) => {
      state.tiposproyectos = tiposproyectos;
    },
    setUbigeo: (state, ubigeos) => {
      state.ubigeos = ubigeos;
    },
    setMoneda: (state, moneda) => {
      state.moneda = moneda;
    },
    setAreaIntegrante: (state, areaintegrante) => {
      state.areaintegrante = areaintegrante;
    },
    setRolIntegrante: (state, rolintegrante) => {
      state.rolintegrante = rolintegrante;
    },

    setUserName: (username) => {
      state.user.name = username;
    },
    setColOcultas: (state, data) => {
      state.hiddenCols = data.split(",");
    },

    setUtilitarios: (state, utils) => {
      state.rolintegrante  = utils["proyRolIntegrante"];
      state.areaintegrante = utils["areaIntegrante "]
      state.moneda         = utils["confMoneda"]
      state.ubigeos        = utils["confUbigeo"]
      state.tiposproyectos = utils["proyTipoProyecto"]
      state.programmingDayTypes = utils["proyTipoDiaProgramacion"]
    },

    setInfoPerson: (state, infoperson) => {

      //  state.infoPerson.data = infoperson;
       state.user.data             = infoperson[0]
       state.infoPerson.data_save  = infoperson[0]
      // console.log(">>> aqui estamos actualizando data")
      // console.log(state.infoPerson.data_save)
      // state.infoPerson.push(infoperson);
    },
    // copyRestriction(state) {
    //   console.log(state.restriction_rows)
    //   state.project_rows = state.restriction_rows;
    // },
    notify: (state, {message, type}) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000)
    },
    /* copyProjectData(state, projectData) {
      const tempProject = {
        id: state.restriction_rows.length+1,
        data: true,
        projectName: projectData.projectName,
        restriction: {
          delayed: 60,
          notDelayed: 40,
        },
        equipments: [],
        users: projectData.userInvData,
      }
      projectData.userInvData.forEach(user => {
        tempProject.equipments.push(user.userEmail)
      });
      state.restriction_rows.push(tempProject)
    }, */
    copyProjectFromDB(state, projectData) {
      const tempProject = {
        id: state.restriction_rows.length+1,
        projectId: projectData.codProyecto,
        data: projectData.desTipoProyecto === 'Abierto' ? true:false,
        projectName: projectData.desNombreProyecto,
        isInvitado : projectData.isInvitado,
        codEstado  : projectData.codEstado,
        restriction: {
          delayed: 60,
          notDelayed: 40,
        },
        equipments: [],
        users: [],
        date: projectData.dayFechaCreacion
      }
      const struser = projectData.desUsuarioCreacion;
      //tempProject.equipments = struser.substr(0, struser.length-1).split(', ');
      if(struser){
        tempProject.users = struser.substr(0, struser.length-1).split(', ');
        state.project_rows.push(tempProject)
        // state.project_rows = state.restriction_rows;
      }
    },
    setCurrentReport(state, ReportData) {
      state.currentprojectreport = ReportData
      console.log(state.currentprojectreport)
    },
    setCurrentUsers(state, ReportData) {
      state.currentprojectusers = ReportData
    },
    setAnaResData(state, ResData) {
      state.whiteproject_rows = ResData
    },
    setAnaResDataMembers(state, ResData) {
      state.anaDataMembers = ResData
    },
    setEstado(state, ResData) {
      state.anaEstado = ResData
    },
    setSolicitanteActual(state, ResData){
      state.solicitanteActual = ResData;
    },
    setRolProyecto(state, ResData){
      state.rolProyecto = ResData;
    },
    setAreaUsuario(state, ResData){
      state.areaUsuario = ResData;
    },
    setEstadoRestriccion(state, ResData){
      state.estadoRestriccion = ResData

    },

    Set_Restriction(state, ResData) {
      state.Restrictionlist_P = ResData;
      ResData.map( (i) => {
        state.Restrictionlist.push({name: i.desTipoRestricciones, value: i.codTipoRestricciones})
      })
    },
    /* Set tipos dia programacion */
    // setProgrammingDayTypes (state, ResData) {
    //   state.programmingDayTypes = ResData
    // },
    setNotification(state, ResData) {
      state.notifications = ResData;
    }

  },
  modules: {},
});

export default store;
