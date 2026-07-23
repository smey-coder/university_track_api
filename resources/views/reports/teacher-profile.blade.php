<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Teacher Profile Report</title>

<style>

*{

margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;

}

body{

padding:40px;

}

.header{

text-align:center;

margin-bottom:30px;

}

.header h3{

color:#2563eb;

margin-top:10px;

}

.profile{

display:flex;

gap:30px;

margin-bottom:30px;

align-items:center;

}

.profile img{

width:170px;
height:170px;
border-radius:50%;
object-fit:cover;
border:5px solid #2563eb;

}

.info{

flex:1;

}

.info h2{

margin-bottom:10px;

}

table{

width:100%;

border-collapse:collapse;

}

table td{

border:1px solid #ccc;

padding:12px;

}

.label{

width:220px;

background:#f1f5f9;

font-weight:bold;

}

.footer{

margin-top:60px;

display:flex;

justify-content:flex-end;

}

.signature{

width:220px;

text-align:center;

}

.signature p{

margin-top:70px;

border-top:1px solid #000;

}

@media print{

@page{

size:A4;

margin:20mm;

}

}

</style>

</head>

<body>

<div class="header">

{{-- <img src="{{ public_path('images/logo.png') }}" width="90"> --}}

<h2>NORTON UNIVERSITY</h2>

<h4>Faculty of Information Technology</h4>

<h3>TEACHER PROFILE REPORT</h3>

</div>

<div class="profile">

@if($teacher->photo)

<img src="{{ asset('storage/'.$teacher->photo) }}">

@else

<img src="https://via.placeholder.com/170">

@endif

<div class="info">

<h2>

{{ $teacher->first_name_english }}

{{ $teacher->last_name_english }}

</h2>

<p>

Teacher Code :

<strong>

{{ $teacher->teacher_code }}

</strong>

</p>

<p>Status : {{ $teacher->status }}</p>

<p>Department : {{ optional($teacher->department)->department_name_english }}</p>

</div>

</div>

<table>

<tr>

<td class="label">Teacher Code</td>

<td>{{ $teacher->teacher_code }}</td>

</tr>

<tr>

<td class="label">English Name</td>

<td>

{{ $teacher->first_name_english }}

{{ $teacher->last_name_english }}

</td>

</tr>

<tr>

<td class="label">Khmer Name</td>

<td>

{{ $teacher->first_name_khmer }}

{{ $teacher->last_name_khmer }}

</td>

</tr>

<tr>

<td class="label">Department</td>

<td>{{ optional($teacher->department)->department_name_english }}</td>

</tr>

<tr>

<td class="label">Gender</td>

<td>{{ $teacher->gender }}</td>

</tr>

<tr>

<td class="label">Date of Birth</td>

<td>{{ $teacher->date_of_birth }}</td>

</tr>

<tr>

<td class="label">Hire Date</td>

<td>{{ $teacher->hire_date }}</td>

</tr>

<tr>

<td class="label">Phone</td>

<td>{{ $teacher->phone }}</td>

</tr>

<tr>

<td class="label">Email</td>

<td>{{ $teacher->email }}</td>

</tr>

<tr>

<td class="label">Address</td>

<td>{{ $teacher->address }}</td>

</tr>

<tr>

<td class="label">Status</td>

<td>{{ $teacher->status }}</td>

</tr>

</table>

<div class="footer">

<div class="signature">

<p>

Administrator

</p>

</div>

</div>

<script>

window.onload=function(){

window.print();

}

</script>

</body>

</html>