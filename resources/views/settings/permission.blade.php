<div class="tab-content" id="myTabContent">

    <!-- Roles & Permission -->
    <div class="container px-0">
        <div class="container-fluid mt-4 listtable">
            <div class="custom-search-container filter-container row mb-3">
                <div class="col-sm-12 col-md-12">
                    <select class="form-select rounded-2">
                        <option value="" selected disabled>Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role }} - {{ $role->role_des }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <form id="myForm" action="" method="Post">
                @csrf
                <input type="hidden" name="role" id="Role_id">
                <div class="table-wrapper">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Module Name</th>
                                <th>Show</th>
                                <th>Profile View</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Request To</th>
                                <th>Esclate To</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="permission_details">
                            <tr>
                                <td>1</td>
                                <td>General Manager Overview</td>
                                <td>
                                    <div><input type="checkbox" name="data[0]module_name[]" id="module_name"
                                            value="General Manager Overview"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>General Manager My Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[1]module_name[]" id="module_name"
                                            value="General Manager My Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>HR Overview</td>
                                <td>
                                    <div><input type="checkbox" name="data[2]module_name[]" id="module_name"
                                            value="HR Overview">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>HR My Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[3]module_name[]" id="module_name"
                                            value="HR My Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>HR KPI Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[4]module_name[]" id="module_name"
                                            value="HR KPI Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Cluster Manager Overview</td>
                                <td>
                                    <div><input type="checkbox" name="data[5]module_name[]" id="module_name"
                                            value="Cluster Manager Overview"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Cluster Manager My Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[6]module_name[]" id="module_name"
                                            value="Cluster Manager My Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Store Strength Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[7]module_name[]" id="module_name"
                                            value="Store Strength Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Area Manager Overview</td>
                                <td>
                                    <div><input type="checkbox" name="data[8]module_name[]" id="module_name"
                                            value="Area Manager Overview"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Area Manager My Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[9]module_name[]" id="module_name"
                                            value="Area Manager My Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Area Manager KPI Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[10]module_name[]" id="module_name"
                                            value="Area Manager KPI Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Operational Manager Overview</td>
                                <td>
                                    <div><input type="checkbox" name="data[11]module_name[]" id="module_name"
                                            value="Area Manager KPI Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Operational Manager My Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[12]module_name[]" id="module_name"
                                            value="Operational Manager My Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Store Manager Overview</td>
                                <td>
                                    <div><input type="checkbox" name="data[13]module_name[]" id="module_name"
                                            value="Store Manager Overview"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Store Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[14]module_name[]" id="module_name"
                                            value="Store Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Store Manager My Dashboard</td>
                                <td>
                                    <div><input type="checkbox" name="data[15]module_name[]" id="module_name"
                                            value="Store Manager My Dashboard"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Employee My Dashbaord</td>
                                <td>
                                    <div><input type="checkbox" name="data[16]module_name[]" id="module_name"
                                            value="Employee My Dashbaord"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Store</td>
                                <td>
                                    <div><input type="checkbox" name="data[17]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[17]permission_show[]" id="show"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[19]permission_show[]" id="view">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[17]permission_add[]" id="add"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[17]permission_edit[]" id="edit"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[17]permission_delete[]" id="delete">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Employee</td>
                                <td>
                                    <div><input type="checkbox" name="data[18]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[18]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[19]permission_show[]" id="view">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[18]permission_add[]" id="add"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[18]permission_edit[]" id="edit">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[18]permission_delete[]" id="delete">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Task</td>
                                <td>
                                    <div><input type="checkbox" name="data[19]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[19]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[19]permission_show[]" id="view">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[19]permission_add[]" id="add"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[19]permission_edit[]" id="edit">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[19]permission_delete[]" id="delete">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Recruitment</td>
                                <td>
                                    <div><input type="checkbox" name="data[20]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[20]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div><input type="checkbox" name="data[20]permission_add[]" id="add"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[20]permission_edit[]" id="edit">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[20]permission_delete[]" id="delete">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Payroll</td>
                                <td>
                                    <div><input type="checkbox" name="data[21]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[21]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div><input type="checkbox" name="data[21]permission_add[]" id="add"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[21]permission_edit[]" id="edit">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[21]permission_delete[]" id="delete">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Daily Attendance</td>
                                <td>
                                    <div><input type="checkbox" name="data[22]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[22]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div><input type="checkbox" name="data[22]permission_add[]" id="add"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[22]permission_edit[]" id="edit">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[22]permission_delete[]" id="delete">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Monthly Attendance</td>
                                <td>
                                    <div><input type="checkbox" name="data[23]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[23]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div><input type="checkbox" name="data[23]permission_add[]" id="add"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[23]permission_edit[]" id="edit">
                                    </div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[23]permission_delete[]" id="delete">
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>25</td>
                                <td>Leave Request</td>
                                <td>
                                    <div><input type="checkbox" name="data[24]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[24]permission_show[]" id="show">
                                    </div>
                                </td>

                                <td></td>
                                <td>
                                    <div><input type="checkbox" name="data[24]permission_add[]" id="add"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>26</td>
                                <td>Repair Request</td>
                                <td>
                                    <div><input type="checkbox" name="data[25]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[25]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div><input type="checkbox" name="data[25]permission_add[]" id="add"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>27</td>
                                <td>Transfer Request</td>
                                <td>
                                    <div><input type="checkbox" name="data[26]module_name[]" id="module_name"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox" name="data[26]permission_show[]" id="show">
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div><input type="checkbox" name="data[26]permission_add[]" id="add"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>28</td>
                                <td>Resignation Request</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>Recruitment Request</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>30</td>
                                <td>Leave Approval</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>31</td>
                                <td>Repair Approval</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>32</td>
                                <td>Transfer Approval</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>33</td>
                                <td>Resignation Approval</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>34</td>
                                <td>Recruitment Approval</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>35</td>
                                <td>Category</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>36</td>
                                <td>Sub Category</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>37</td>
                                <td>Password</td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td>
                                    <div><input type="checkbox"></div>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="col-12 d-flex justify-content-center align-items-center gap-md-2 mt-3">
                <button class="addbtn">Save</button>
                <button class="deletebtn">Cancel</button>
            </div>
        </div>

    </div>

</div>
<script type="text/javascript">
    function roleChange() {
        var role = $('#role').val();
        var route = "{{ route('permission.filter', '') }}" + "/" + role;
        window.location.href = route;
    }

    $(document).ready(function() {
        @if (isset($uniqueId))
            var response1 = @json($filter);
            var response2 = @json($uniqueId);
        @endif
        $('#role').val(response2);

        if (response1) {
            var role = @json($role);

            for (var i = 0; i < response1.length; i++) {
                $('.permission_details tr').each(function() {
                    var row = $(this);
                    var Module_name = row.find("#module_name").val();

                    if (Module_name == response1[i].module_name) {
                        row.find("#add").prop("checked", response1[i].permission_add == "1");
                        row.find("#view").prop("checked", response1[i].permission_view == "1");
                        row.find("#edit").prop("checked", response1[i].permission_edit == "1");
                        row.find("#delete").prop("checked", response1[i].permission_delete == "1");
                        row.find("#recommend").prop("checked", response1[i].permission_recommend ==
                            "1");
                        row.find("#verify").prop("checked", response1[i].permission_verify == "1");
                        row.find("#approval").prop("checked", response1[i].permission_approval == "1");
                        row.find("#show").prop("checked", response1[i].permission_show == "1");
                    }
                });
            }
        }

        $(".submit").click(function(event) {
            event.preventDefault();
            $('.addbtn').addClass('disabled');

            var permission = [];
            var roles = {};

            roles.role = $('#role').val();

            if (!roles.role) {
                alert('Please Select Role...!');
                return;
            }

            var checked = $("input[type=checkbox]:checked").length;
            if (!checked) {
                alert('Please Select Any Checkbox...!');
                return;
            }

            $('.permission_details tr').each(function() {
                var row = $(this);
                var permissions = {};

                permissions.Module_name = row.find("#module_name").val();
                permissions.add_form = row.find("#add").is(":checked") ? '1' : '0';
                permissions.view_form = row.find("#view").is(":checked") ? '1' : '0';
                permissions.edit_form = row.find("#edit").is(":checked") ? '1' : '0';
                permissions.delete_form = row.find("#delete").is(":checked") ? '1' : '0';
                permissions.recommend_form = row.find("#recommend").is(":checked") ? '1' : '0';
                permissions.verify_form = row.find("#verify").is(":checked") ? '1' : '0';
                permissions.approval_form = row.find("#approval").is(":checked") ? '1' : '0';
                permissions.show_form = row.find("#show").is(":checked") ? '1' : '0';

                permission.push(permissions);
            });

            // Send data as JSON
            $.ajax({
                url: "{{ route('permission.store') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}", // Include the token in headers
                    'Content-Type': 'application/json' // Set content type as JSON
                },
                data: JSON.stringify({
                    role: roles.role,
                    permissions: permission
                }),
                success: function(response) {
                    alert("Permissions saved successfully!");
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    alert("An error occurred while saving permissions.");
                    console.error(xhr.responseText);
                }
            });
        });

    });
</script>
