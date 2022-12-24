<!DOCTYPE html>
<html lang="en">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        #show {
            width: 60%;
            padding-top: 3%;
            margin-left: 37%;
            margin-top: -42%;
            padding-left: 3%;
            padding-right: 3%;
            padding-bottom: 2%;
        }

        .contain {
            margin-left: -16%;
        }

        .error {
            color: red;
        }

        #pic {
            width: 200px;
            height: 50px;
        }

        #picbutton {
            margin-top: 202px;
        }
    </style>

    @include('cdn')

    <title>Document</title>
</head>

<body>
    @include('flash-message')

    <h2 style="text-align:center ;">Laravel Ajax Dynamic Dependency Crud</h2>
    <h2 class="h2">{{$title}}</h2>

    <div class="contain">

        <form class="dynamic">
            @csrf
            <div class="container">
                <!--Username-->
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username"></input><br>
                <span style="color:red;">@error('username'){{$message}}@enderror</span>
                <input type="hidden" name="userid" id="userid">

                <!-- Country dropdown -->
                <div class="form-group">
                    <label for="country">Country</label><br>
                    <select id="country" name="country" onchange="getState(this.value);">
                        <option value="">Select Country</option>
                    </select>
                    <span style="color:red;">@error('country'){{$message}}@enderror</span><br>
                </div>

                <!-- State dropdown -->
                <div class="form-group">
                    <label for="state">State</label><br>
                    <select id="state" name="state" onchange="getCity(this.value);">
                        <option value="">Select country first</option>
                    </select>
                    <span style="color:red;">@error('state'){{$message}}@enderror</span><br>
                </div>

                <!-- City dropdown -->
                <div class="form-group">
                    <label for="city">City</label><br>
                    <select id="city" name="city">
                        <option value="">Select state first</option>
                    </select>
                    <span style="color:red;">@error('city'){{$message}}@enderror</span><br>
                </div>

                <div class="col-xl-4" id="pic">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <!-- Profile picture image-->
                            <img class="img-account-profile rounded-circle mb-2" src="http://bootdey.com/img/Content/avatar/avatar1.png" width="96" height="96" alt="" id="previewProfilePic">
                            <!-- Profile picture help block-->
                            <div class="small font-italic text-muted mb-4">JPG or PNG not larger than 5 MB</div>
                            <!-- Profile picture upload button-->

                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" id="picbutton" type="button">
                    <input class="file-upload-input error-replace" type='file' name="photo" onclick="getImage()" accept="image/*" id="userProfilePic" name="userProfilePic" />
                </button>
                <br>

                <div id="imageDiv">
                </div>
                <br>

                <button type="submit" id="submit" name="submit" value="{{$submit}}">{{$submit}}</button>

            </div>
        </form>
    </div>
    <div class="col-md-8" id="show">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="info">

                </tbody>
            </table>
        </div>
    </div>

</body>

</html>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });


    getData();

    function getData() {
        $.ajax({
            url: "{{url('/getData')}}",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                // console.log(response.userData);
                var main_content = '';
                if (response.userData.length > 0) {
                    $.each(response.userData, function(index, row) {
                        //console.log(row.userName);
                        main_content +=
                            `<tr>
                    <td>` + (++index) + `</td>
                    <td>` + row.userName + `</td>
                    <td>` + row.countryName + `</td>
                    <td>` + row.stateName + `</td>
                    <td>` + row.cityName + `</td>
                    <td>
                    <button type="button" onclick="setUserInfo(` + row.user_id + `);" class="btn btn-info me-2">Edit</button>
                    <button type="button" onclick="removeUserInfo(` + row.user_id + `);" class="btn btn-warning">Delete</button>
                    </td>
                    </tr>`;
                        //console.log(main_content);
                    })
                } else {
                    main_content = `No Data Found!`
                }

                $('#info').html(main_content);

            }
        });
    }


    $(".dynamic").validate({
        rules: {
            username: {
                required: true,
                remote: { // -------- Name Remote Validation --------  //
                    url: "{{url('/nameValidation')}}",
                    type: "post",
                    userName: function() {
                        return $("#username").val();
                    },
                },
            },
            country: {
                required: true,
            },
            state: {
                required: true,
            },
            city: {
                required: true,
            },
            photo: {
                required: true,
            },
        },
        messages: {
            username: {
                required: "Please Enter the field ",
                remote: "User already exist",
            },
            country: {
                required: "Please Enter Country ",
            },
            state: {
                required: "Please Enter State ",
            },
            city: {
                required: "Please Enter City ",
            },
            photo: {
                required: "Please Enter Photo ",
            },
        },
        submitHandler: function(form) {
            $.ajax({
                url: "{{$url}}",
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: new FormData($('.dynamic')[0]),
                success: function(response) {
                    // console.log(response);
                    if (response.status == 200) {
                        $('#submit').html('Save');
                        $('.dynamic')[0].reset();
                        $('#userid').val('');
                        $('#country').val('');
                        $('#state').val('');
                        $('#city').val('');
                        getData();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    getCountry();

    function getCountry(countryId = '') {
        $.ajax({
            type: 'GET',
            url: "{{url('/getCountry')}}",
            dataType: 'json',
            success: function(response) {
                var html = '<option value="">Select country first</option>';
                // console.log(response.countryList);
                if (response.countryList.length > 0) {
                    $.each(response.countryList, function(index, row) {
                        var select = '';
                        if (countryId != '' && countryId == row.id) {
                            select = 'selected';
                        }
                        html += `<option ` + select + ` value="` + row.id + `">` + row.countryName + `</option>`;
                    });
                }
                $('#country').html(html);
            }
        });
    }

    function getState(countryID, stateId = '') {

        if (countryID) {
            $.ajax({
                type: 'GET',
                url: "{{url('/getState')}}",
                dataType: 'json',
                data: 'country_id=' + countryID,
                success: function(response) {
                    var html = '<option value="">Select country first</option>';
                    // $('#city').find('option:not(:first)').remove();
                    if (response.stateList.length > 0) {
                        $.each(response.stateList, function(index, row) {
                            var select = '';
                            if (stateId != '' && stateId == row.id) {
                                select = 'selected';
                            }
                            html += `<option ` + select + ` value="` + row.id + `">` + row.stateName + `</option>`;
                        })
                    }
                    $('#state').html(html);
                }
            });
        }
    }

    function getCity(stateID, cityId = '') {

        if (stateID) {
            $.ajax({
                type: 'GET',
                url: "{{url('/getCity')}}",
                dataType: 'json',
                data: 'state_id=' + stateID,
                success: function(response) {
                    var html = '<option value="">Select state first</option>';
                    if (response.cityList.length > 0) {
                        $.each(response.cityList, function(index, row) {
                            var select = '';
                            if (cityId != '' && cityId == row.id) {
                                select = 'selected';
                            }
                            html += `<option ` + select + ` value="` + row.id + `">` + row.cityName + `</option>`;
                        })
                    }
                    $('#city').html(html);
                }
            });
        }
    }


    function setUserInfo(user_id) {
        $.ajax({
            url: "{{url('/getInfoById')}}",
            type: 'get',
            dataType: 'json',
            data: {
                user_id: user_id
            },
            success: function(response) {
                // console.log(response.userData[0]);
                for (let i = 0; i < response.userData.length; i++) {
                    $('#submit').val(response.submit);
                    $('#submit').html(response.submit);
                    $('.h2').html(response.title);
                    $('#username').val(response.userData[i].userName);
                    $('#userid').val(response.userData[i].user_id);
                    if (response.userData[i].profilePicture) {
                        $("img").attr("src", response.userData[i].profilePicture);
                    }
                    getCountry(response.userData[i].country);
                    getState(response.userData[i].country, response.userData[i].state);
                    getCity(response.userData[i].state, response.userData[i].city);
                }
            }
        });
    }

    function removeUserInfo(user_id) {
        if (confirm("Are you sure?")) {
            $.ajax({
                url: "{{url('/deleteData')}}",
                type: 'post',
                dataType: 'json',
                data: {
                    user_id: user_id
                },
                success: function(response) {
                    // console.log(response.status);
                    if (response.status == 200) {
                        getData();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    function getImage() {
        $('#userProfilePic').change(function() {
            const file = this.files[0];
            // console.log(file);
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    // console.log(event.target.result);
                    $('#previewProfilePic').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    }


    window.onload = function() {
        var fileUpload = document.getElementById("userProfilePic");
        fileUpload.onchange = function() {
            if (typeof(FileReader) != "undefined") {
                var dvPreview = document.getElementById("userProfilePic");
                dvPreview.innerHTML = "";
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                for (var i = 0; i < fileUpload.files.length; i++) {
                    var file = fileUpload.files[i];
                    if (regex.test(file.name.toLowerCase())) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var img = document.createElement("IMG");
                            img.height = "100";
                            img.width = "100";
                            img.src = e.target.result;
                            document.getElementById("imageDiv").appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    } else {
                        alert(file.name + " is not a valid image file.");
                        dvPreview.innerHTML = "";
                        return false;
                    }
                }
            } else {
                alert("This browser does not support HTML5 FileReader.");
            }
        }
    };
</script>