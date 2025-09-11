<div class="row">
    <div class="col-md-12  stretch-card">
        <div class="card">              
            <div class="card-body">
                <h5 class="text-center mb-4">Send message for request rejection</h5>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputUsername2">Enter Message</label>
                            <textarea rows="5" id="message" class="form-control" name="message" placeholder="Enter your message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="credit_request_id" value="{{$credit_request_id}}">
                            <button type="button" class="btn btn-primary send-message mr-2">SEND MESSAGE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>