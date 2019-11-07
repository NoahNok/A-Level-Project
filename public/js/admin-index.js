const spinner = new Vue({
    el: '#spinner',
    data: {
        hidden: true,
    },
    methods: {
        show: function(){
            this.hidden = false;
        },
        hide: function(){
            this.hidden = true;
        }
    }
});

const users = new Vue({
    el: '#users',
    data: {
        users: {},
        dis: false,
    },
    mounted() {
        spinner.show();
        let request = new RRequest('/api/users/all', 'GET');
        let clazz = this;
        request.onResponse = function (data) {
            clazz.users = data;
            if (users.dis){
                spinner.hide();
                users.dis = false;
            }
        }

        request.send();
    },
    methods: {
        edit: function(userid){
            userEdit.switchUser(userid);
        }
    }
});

const forms = new Vue({
    el: '#forms',
    data: {
        forms: {},
        dis: false,
    },
    mounted() {
        spinner.show();
        let request = new RRequest('/api/forms/all', 'GET');
        let clazz = this;
        request.onResponse = function (data) {
            clazz.forms = data;
            userEdit.forms = data;
            if (forms.dis) {
                spinner.hide();
                forms.dis = true;
            }
        }

        request.send();
    },
    methods: {
        edit: function(formid){
            formEdit.switchForm(formid);
        },
        newForm: function(){
            newForm.newForm();
        }
    }
});

const years = new Vue({
    el: '#years',
    data: {
        years: {},
        dis: false,
    },
    mounted() {
        let request = new RRequest('/api/years/all', 'GET');
        spinner.show();
        let clazz = this;
        request.onResponse = function (data) {
            clazz.years = data;
            userEdit.years = data;
            if (years.dis) {
                spinner.hide();
                years.dis = true;
            }
        }

        request.send();
    },
    methods: {
        edit: function(yearid){
            yearEdit.switchYear(yearid);
        },
        newYear: function(){
            newYear.newYear();
        },
    }
});

const userEdit = new Vue({
   el: '#userEdit',
   data: {
       user: {},
       years: {},
       forms: {},
       roles: {},
       year_selected: null,
       form_selected: null,
       role_selected: null,
       loading: false,
   },
    methods: {
       switchUser: function(id){
           if (this.loading) return;
           this.loading = true;
           let request = new RRequest(`/api/user/${id}`, 'GET');
           let clazz = this;
           spinner.show();
           request.onResponse = function (data){
               userEdit.user = data;
               userEdit.year_selected = data.year;
               userEdit.form_selected = data.form;
               userEdit.role_selected = data.role.id;
               spinner.hide();
               $('#userEdit').modal('show');
               userEdit.loading = false;
           };

           request.onError = function(){
               // Add fail safe incase request is bad. This way you wont be locked out of trying to load users.
               userEdit.loading = false;
               spinner.hide();
           }
           request.send();
       },
       saveUser: function(){
           for (el in userEdit.$refs){
               userEdit.$refs[el].classList.remove('is-invalid');
           }
           let user = userEdit.user;
           // If any of these are null or on empty we cancel the save. We ignore Year, Form and Role as Year & Form can be NULL and Role will default to Student if not selected
            if (user.firstName === "" || user.firstName === null || user.lastName === "" || user.lastName === null || user.email === "" || user.email === null){
                return;
            }

            // Request
           let request = new RRequest('/api/users/edit/'+user.id, 'POST');
           request.onResponse = function(data){
               if (!handleErrors(data, userEdit.$refs)){
                   return;
               }
               users.dis = true;
               users.$mount();
               $('#userEdit').modal('hide');
           };
           let formData = new FormData();
           formData.append('firstName', user.firstName);
           formData.append('lastName', user.lastName);
           formData.append('form', userEdit.form_selected);
           formData.append('year', userEdit.year_selected);
           formData.append('role', userEdit.role_selected);
           formData.append('email', user.email);
           formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
           request.send(formData);
       },
        deleteUser: function(){
           let request = new RRequest('/api/user/delete', 'POST');
           spinner.show();
           request.onResponse = function(data){
               spinner.hide();
               users.dis = true;
               users.$mount();
               $('#userEdit').modal('hide');
           };

           let data = new FormData();
           data.append('_method', 'DELETE');
           data.append('_token', $('meta[name="csrf-token"]').attr('content'));
           data.append('id', userEdit.user.id);

           request.send(data);
        }
    },
    mounted() {
        let request = new RRequest(`/api/roles`, 'GET');
        let clazz = this;
        request.onResponse = function (data){
            clazz.roles = data;
            spinner.hide();
        }
        request.send();
    }
});

const formEdit = new Vue({
   el: '#formEdit',
   data: {
       form: {},
   },
   methods: {
       switchForm(formid){
           spinner.show();
           let request = new RRequest(`/api/form/${formid}`, 'GET');
           request.onResponse = function(data){
             formEdit.form = data;
             spinner.hide();
             $('#formEdit').modal('show');
           };
           request.send();
       },
       saveForm(){
           if (formEdit.form.form === null || formEdit.form.form === "") return;
           let request = new RRequest(`/api/forms/edit/${formEdit.form.id}`, 'POST');
           spinner.show();
           request.onResponse = function (data) {
             spinner.hide();
             forms.dis = true;
             forms.$mount();
             users.dis = true;
             users.$mount();
             $('#formEdit').modal('hide');
           };

           let data = new FormData();
           data.append('form_name', formEdit.form.form);
           data.append('_token', $('meta[name="csrf-token"]').attr('content'));
           request.send(data);

       },
       deleteForm(){
           let request = new RRequest('/api/form/delete', 'POST');
           spinner.show();
           request.onResponse = function(data){
               spinner.hide();
               forms.dis = true;
               forms.$mount();
               users.dis = true;
               users.$mount();
               $('#formEdit').modal('hide');
           }

           let data = new FormData();
           data.append('_method', 'DELETE');
           data.append('_token', $('meta[name="csrf-token"]').attr('content'));
           data.append('id', formEdit.form.id);

           request.send(data);
       },
   }
});

const yearEdit = new Vue({
    el: '#yearEdit',
    data: {
        year: {},
    },
    methods: {
        switchYear(yearid){
            spinner.show();
            let request = new RRequest(`/api/year/${yearid}`, 'GET');
            request.onResponse = function(data){
                yearEdit.year = data;
                spinner.hide();
                $('#yearEdit').modal('show');
            };
            request.send();
        },
        saveYear(){
            if (yearEdit.year.year === null || yearEdit.year.year === "") return;
            let request = new RRequest(`/api/years/edit/${yearEdit.year.id}`, 'POST');
            spinner.show();
            request.onResponse = function (data) {
                spinner.hide();
                years.dis = true;
                years.$mount();
                users.dis = true;
                users.$mount();
                $('#yearEdit').modal('hide');
            };

            let data = new FormData();
            data.append('year_name', yearEdit.year.year);
            data.append('_token', $('meta[name="csrf-token"]').attr('content'));
            request.send(data);

        },
        deleteYear(){
            let request = new RRequest('/api/year/delete', 'POST');
            spinner.show();
            request.onResponse = function(data){
                spinner.hide();
                years.dis = true;
                years.$mount();
                users.dis = true;
                users.$mount();
                $('#yearEdit').modal('hide');
            }

            let data = new FormData();
            data.append('_method', 'DELETE');
            data.append('_token', $('meta[name="csrf-token"]').attr('content'));
            data.append('id', yearEdit.year.id);

            request.send(data);
        },
    }
});

const newForm = new Vue({
   el: '#formNew',
   data: {
       form: "",
   },
   methods: {
       newForm(){
           $('#formNew').modal('show');
           newForm.form = "";
       },
       newFormSave(){
           if (newForm.form === null || newForm.form === "") return;
           let request = new RRequest('/api/forms/create', 'POST');
           spinner.show();
           request.onResponse = function(data){
               spinner.hide();
               forms.dis = true;
               forms.$mount();
               $('#formNew').modal('hide');
           }

           let data = new FormData();
           data.append('_token', $('meta[name="csrf-token"]').attr('content'));
           data.append('form_name', newForm.form);

           request.send(data);
       }
   },
});

const newYear = new Vue({
    el: '#yearNew',
    data: {
        year: "",
    },
    methods: {
        newYear(){
            $('#yearNew').modal('show');
            newYear.year = "";
        },
        newYearSave(){
            if (newYear.year === null || newYear.year === "") return;
            let request = new RRequest('/api/years/create', 'POST');
            spinner.show();
            request.onResponse = function(data){
                spinner.hide();
                years.dis = true;
                years.$mount();
                $('#yearNew').modal('hide');
            }

            let data = new FormData();
            data.append('_token', $('meta[name="csrf-token"]').attr('content'));
            data.append('year_name', newYear.year);

            request.send(data);
        }
    },
});

let g;

function handleErrors(data, errorElements){
    if (data.includes('success')){
        return true;
    }
    data = JSON.parse(data);

    data.forEach(error => {
       switch(error){
           case "form-not-found":
               errorElements.form.classList.add('is-invalid');
               break;
           case "year-not-found":
               errorElements.year.classList.add('is-invalid');
               break;
           case "email":
               errorElements.email.classList.add('is-invalid');
               break;
       }
    });

    return false;
}


