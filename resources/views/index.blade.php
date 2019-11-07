@extends('layouts.app')
@section('title', "SportsDay")
@section('content')
<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
if (auth()->user() !== null) {
  echo auth()->user()->can('teach') ? "TRUE":"FALSE";
}


?>
@endsection
