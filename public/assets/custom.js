$(".manualIdeal,.showModal").click(function () {
    if ($("#manualIdealTime").length > 0) {
        var odid = $(this).data("odid");
        var operationid = $(this).data("operationid");

        $("#odid").val(odid);
        $("#operationid").val(operationid);
        $("#manualIdealTime").modal("show");
    }

    $("body").attr("style", "");
    $("body").addClass("modal-open");
});

$(document).on(
    "click",
    "button[data-dismiss='modal'],button[data-bs-dismiss='modal']",
    function () {
        if ($("#manualIdealTime").length > 0) {
            $("#manualIdealTime").modal("hide");
        }
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
    }
);

$(".paramsSelect").change(function () {
    var selectedValue = $(this).val();
    var unitForm = $(".unitForm");
    unitForm.empty();
    if (selectedValue) {
        var fields = selectedValue.split(",");

        fields.forEach(function (field) {
            var arrayRecord = field.trim().split(" ");
            var unitName = arrayRecord.join(" ");
            unitForm.append("<label>" + unitName + "</label>");
            unitForm.append(
                '<input type="number" data-unit="' +
                    unitName +
                    '" min="0" class="form-control manualUnit" placeholder="Enter ' +
                    unitName +
                    '">'
            );
        });
        unitForm.append("<label>Cycle Time</label>");
        unitForm.append(
            '<input type="number" class="form-control manualTime" min="0" placeholder="Cycle Time" disabled>'
        );
    }

    $(document).on("keydown", ".manualUnit", function (e) {
        if ($.inArray(e.keyCode, [8, 9, 46, 37, 39]) !== -1) {
            return;
        }
        if (
            (e.keyCode < 48 || e.keyCode > 57) &&
            (e.keyCode < 96 || e.keyCode > 105)
        ) {
            e.preventDefault();
        }
    });

    $(".manualUnit").on("keyup", function () {
        calculateCycleTime();
    });
    function calculateCycleTime() {
        var total = 0;

        var unitValues = {};
        $(".manualUnit").each(function () {
            var value = parseFloat($(this).val());
            var unit = $(this).data("unit");
            unitValues[unit] = value;
        });

        var odid = $("#odid").val();
        var operationid = $("#operationid").val();

        var token = $("meta[name='csrf-token']").attr("content");
        var url = window.location.origin;
        if (window.location.hostname === "localhost") {
            url += "/deetee/admin/calculateCycleTime";
        } else {
            url += "/admin/calculateCycleTime";
        }
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: token,
                odid: odid,
                operationid: operationid,
                unitValues: unitValues,
            },
            success: function (response) {
                total += response;
                $(".manualTime").val(total);
            },
            error: function (xhr, status, error) {
                console.error("Error calculating cycle time:", error);
            },
        });
    }
});


$(".operationTrackingOpen").find(".accordion-button").on("click",function () {
 
    var getOpId = $(this).attr("detail-id");
    url = baseUrl + "/admin/getOperationDetails" + "/" + getOpId;
    console.log(url);

    $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
            $("#oprationTrackingDetails" + getOpId).html(response);
        },
        error: function (xhr, status, error) {
            console.error("Error calculating cycle time:", error);
        },
    });
});
 
$(document).on("click", ".trackingModal", function () {
    var type= $(this).data("type");
    var odid = $(this).data("id");
    $("#odid").val(odid);
    $("#optype").val(type);
    var title = "";
    if(type == "rework"){
         title = "Rework";
    }
    if(type == "approved"){
         title = "Approved";
    }
    if(type == "remarks"){
         title = "Remarks";
    }
    $(".modal-title").text(title);
    $("#reviewModal").modal("show");
})
    