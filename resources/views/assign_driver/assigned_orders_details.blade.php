    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewModalLabel">Assigned Orders</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="driverName">Driver Name:</label>
                                <span id="driverName">Driver 1</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="assignedDate">Assigned Date:</label>
                                <span id="assignedDate">2023-07-20</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- <div class="form-group"> --}}
                            {{-- <label for="assignedRoute">Assigned Route:</label> --}}
                            Assigned Route:<input type="text" class="form-control" id="assignedRoute">
                            {{-- </div> --}}
                        </div>
                    </div>
                    <!-- Add the table here -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Customer</th>
                                <th>Sales Person</th>
                                <th>Amount</th>
                                <th>Stop</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Add your table rows with data here -->
                            <tr>
                                <td>1</td>
                                <td>2023-07-21</td>
                                <td>Completed</td>
                                <td>Customer A</td>
                                <td>Sales Person 1</td>
                                <td>$100.00</td>
                                <td>
                                    <textarea class="form-control" rows="2"></textarea>
                                </td>
                                <td>
                                    <textarea class="form-control" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <!-- Add more rows as needed -->
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of table -->
                </div>
            </div>
        </div>
    </div>
