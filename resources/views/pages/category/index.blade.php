@extends('layouts.default')
@section('css')
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <h5 class="card-title">Category List</h5>
                        <div id="data-table-conf" class="dataTables_wrapper dt-bootstrap4 data-table-conf">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="zero-conf" class="display dataTable" style="width: 100%;" role="grid"
                                        aria-describedby="zero-conf_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="zero-conf"
                                                    rowspan="1" colspan="1" style="width: 246px;"
                                                    aria-sort="ascending"
                                                    aria-label="Name: activate to sort column descending">Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="zero-conf" rowspan="1"
                                                    colspan="1" style="width: 350px;"
                                                    aria-label="Position: activate to sort column ascending">Position</th>
                                                <th class="sorting" tabindex="0" aria-controls="zero-conf" rowspan="1"
                                                    colspan="1" style="width: 186px;"
                                                    aria-label="Office: activate to sort column ascending">Office</th>
                                                <th class="sorting" tabindex="0" aria-controls="zero-conf" rowspan="1"
                                                    colspan="1" style="width: 85px;"
                                                    aria-label="Age: activate to sort column ascending">Age</th>
                                                <th class="sorting" tabindex="0" aria-controls="zero-conf" rowspan="1"
                                                    colspan="1" style="width: 175px;"
                                                    aria-label="Start date: activate to sort column ascending">Start date
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="zero-conf" rowspan="1"
                                                    colspan="1" style="width: 164px;"
                                                    aria-label="Salary: activate to sort column ascending">Salary</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr role="row" class="odd">
                                                <td class="sorting_1">Tiger Nixon</td>
                                                <td>System Architect</td>
                                                <td>Edinburgh</td>
                                                <td>61</td>
                                                <td>2011/04/25</td>
                                                <td>$320,800</td>
                                            </tr>
                                            <tr role="row" class="even">
                                                <td class="sorting_1">Timothy Mooney</td>
                                                <td>Office Manager</td>
                                                <td>London</td>
                                                <td>37</td>
                                                <td>2008/12/11</td>
                                                <td>$136,200</td>
                                            </tr>
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">Unity Butler</td>
                                                <td>Marketing Designer</td>
                                                <td>San Francisco</td>
                                                <td>47</td>
                                                <td>2009/12/09</td>
                                                <td>$85,675</td>
                                            </tr>
                                            <tr role="row" class="even">
                                                <td class="sorting_1">Vivian Harrell</td>
                                                <td>Financial Controller</td>
                                                <td>San Francisco</td>
                                                <td>62</td>
                                                <td>2009/02/14</td>
                                                <td>$452,500</td>
                                            </tr>
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">Yuri Berry</td>
                                                <td>Chief Marketing Officer (CMO)</td>
                                                <td>New York</td>
                                                <td>40</td>
                                                <td>2009/06/25</td>
                                                <td>$675,000</td>
                                            </tr>
                                            <tr role="row" class="even">
                                                <td class="sorting_1">Zenaida Frank</td>
                                                <td>Software Engineer</td>
                                                <td>New York</td>
                                                <td>63</td>
                                                <td>2010/01/04</td>
                                                <td>$125,250</td>
                                            </tr>
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">Zorita Serrano</td>
                                                <td>Software Engineer</td>
                                                <td>San Francisco</td>
                                                <td>56</td>
                                                <td>2012/06/01</td>
                                                <td>$115,000</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th rowspan="1" colspan="1">Name</th>
                                                <th rowspan="1" colspan="1">Position</th>
                                                <th rowspan="1" colspan="1">Office</th>
                                                <th rowspan="1" colspan="1">Age</th>
                                                <th rowspan="1" colspan="1">Start date</th>
                                                <th rowspan="1" colspan="1">Salary</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
<script type="text/javascript"></script>
@section('javascript')
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatables.js') }}"></script>

@stop
