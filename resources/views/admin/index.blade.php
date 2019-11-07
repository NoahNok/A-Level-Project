@extends('layouts.app')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}" xmlns:v-on="http://www.w3.org/1999/xhtml"
          xmlns:v-on="http://www.w3.org/1999/xhtml">
@endsection
@section('title', "Admin - SportsDay")
@section('content')


<div class="container-fluid">
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">Navigation</div>

          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="#">Users, Forms & Years</a></li>
            <li class="list-group-item"><a href="#">Item #2</a></li>
            <li class="list-group-item"><a href="#">Item #3</a></li>
          </ul>

      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">
          Users, Forms & Years
        </div>
        <div class="card-body">
          <h3>Users</h3>
          <div class="table-responsive">
            <table class="table table-striped table-hover" style="max-height: 25vh;" id="users">
              <thead>
                <tr>
                  <th scope="col">Surname</th>
                  <th scope="col">Firstname</th>
                  <th scope="col">Role</th>
                  <th scope="col">Year</th>
                  <th scope="col">Form</th>
                  <th scope="col">Joined</th>

                </tr>
              </thead>

              <tbody>
                <tr v-for="user in users" v-cloak :user-id="user.id" @click="edit(user.id)">
                    <th scope="row">@{{ user.lastName }}</th>
                    <td>@{{ user.firstName }}</td>
                    <td role>@{{ user.role }}</td>
                    <td>@{{ user.year }}</td>
                    <td>@{{ user.form }}</td>
                    <td>@{{ user.joined }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <h3>Forms</h3>
          <div class="table-responsive">
            <table class="table table-striped table-hover" style="max-height: 25vh;" id="forms">
              <thead>
                <tr>
                  <th scope="col">Form <span class="badge badge-primary" @click="newForm">New</span></th>
                  <th scope="col">House</th>
                  <th scope="col">Updated</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="form in forms" :form-id="form.id" v-cloak @click="edit(form.id)">
                  <th scope="row">@{{ form.form }}</th>
                  <td>@{{ form.name }}</td>
                  <td>@{{ form.created }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <h3>Years</h3>
          <div class="table-responsive">
            <table class="table table-striped table-hover" style="max-height: 25vh;" id="years">
              <thead>
                <tr>
                  <th scope="col">Year <span class="badge badge-primary" @click="newYear">New</span></th>
                  <th scope="col">Updated</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="year in years" :year-id="year.id" v-cloak @click="edit(year.id)">
                  <th scope="row">@{{ year.year }}</th>
                  <td>@{{ year.created }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="userEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User: @{{ user.firstName + ' ' + user.lastName }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Firstname</span>
                        </div>
                        <input id="userFirstName" type="text" class="form-control" placeholder="John" v-bind:class="{'is-invalid':(user.firstName === '')}" v-model="user.firstName" ref="fname">
                    </div>

                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Surname</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Smith" v-bind:class="{'is-invalid':(user.lastName === '')}" v-model="user.lastName" ref="sname">
                    </div>


                </div>
                <div class="form-group form-inline">
                    <div class="input-group col-md-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Email</span>
                        </div>
                        <input type="email" class="form-control"  v-model="user.email" placeholder="example@domain.com" v-bind:class="{'is-invalid':(user.email === '')}" ref="email">
                    </div>
                </div>
                <div class="form-group form-inline">
                    <div class="input-group col-md-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Year</span>
                        </div>
                        <select class="form-control custom-select" name="" v-model="year_selected" v-bind:class="{'is-invalid':(year_selected === 'null')}" ref="year">
                            <option selected value="null">Please Select a Year</option>
                            <option :value="year.id" v-for="year in years">@{{ year.year }}</option>
                        </select>
                    </div>

                    <div class="input-group col-md-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Form</span>
                        </div>
                        <select class="form-control custom-select" name="" v-model="form_selected" v-bind:class="{'is-invalid':(form_selected === 'null')}" ref="form">
                            <option selected value="null">Please Select a Form</option>
                            <option :value="form.id" v-for="form in forms">@{{ form.form }}</option>
                        </select>
                    </div>

                    <div class="input-group col-md-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Role</span>
                        </div>
                        <select name="" id="" disabled class="form-control custom-select disabled" v-model="role_selected" v-bind:class="{'is-invalid':(role_selected === 'null')}" ref="role">
                            <option selected value="null">Please Select a Role</option>
                            <option :value="role.id" v-for="role in roles">@{{ role.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group form-inline">


                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" @click="deleteUser">Delete</button>
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" @click="saveUser">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="formEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form: @{{ form.form }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Name</span>
                        </div>
                        <input id="formName" type="text" class="form-control" placeholder="Form" v-bind:class="{'is-invalid':(form.form === '')}" v-model="form.form" ref="formName">
                    </div>
                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">House</span>
                        </div>
                        <input id="formHouse" disabled type="text" class="form-control" placeholder="House" v-bind:class="{'is-invalid':(form.house === '')}" v-model="form.house" ref="formHouse">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" @click="deleteForm">Delete</button>
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" @click="saveForm">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="yearEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Year: @{{ year.year }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Name</span>
                        </div>
                        <input id="yearName" type="text" class="form-control" placeholder="Year" v-bind:class="{'is-invalid':(year.year === '')}" v-model="year.year" ref="yearName">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" @click="deleteYear">Delete</button>
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" @click="saveYear">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="formNew" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form: @{{ form }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Name</span>
                        </div>
                        <input id="formName" type="text" class="form-control" placeholder="Form" v-bind:class="{'is-invalid':(form === '')}" v-model="form" ref="formName">
                    </div>
                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">House</span>
                        </div>
                        <input id="formHouse" disabled type="text" class="form-control" placeholder="House" v-bind:class="{'is-invalid':(form.house === '')}" v-model="form.house" ref="formHouse">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" @click="newFormSave">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="yearNew" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Year: @{{ year }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group form-inline">
                    <div class="input-group col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Name</span>
                        </div>
                        <input id="yearName" type="text" class="form-control" placeholder="Year" v-bind:class="{'is-invalid':(year === '')}" v-model="year" ref="yearName">
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" @click="newYearSave">Save</button>
            </div>
        </div>
    </div>
</div>


<div class="spinner-border" role="status" id="spinner" :hidden="hidden" style="position: fixed; bottom: 2vh; right: 2vh;">
    <span class="sr-only">Loading...</span>
</div>


<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="{{ asset('js/Request.js') }}"></script>
<script src="{{ asset('js/admin-index.js') }}"></script>



@endsection
