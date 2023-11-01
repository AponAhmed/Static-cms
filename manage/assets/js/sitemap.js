jQuery(document).ready(function () {
    jQuery(".update-csvData").click(() => {
        //console.log(loadedCsvFile, currentCsvData);
        updateCsvFile();
    });
});
function generateReportHTML(reportData) {
    // Create a div to hold the report
    var reportDiv = document.createElement("div");

    // Create a heading
    var heading = document.createElement("h2");
    heading.textContent = "Report";

    // Create a paragraph for the message
    var message = document.createElement("p");
    message.textContent = "Message: " + reportData.message;

    // Create a table for the reports
    var table = document.createElement("table");
    table.className = "table";
    var tableHeader = document.createElement("thead");
    var tableBody = document.createElement("tbody");

    // Create table header row
    var headerRow = document.createElement("tr");
    var headerCell1 = document.createElement("th");
    headerCell1.textContent = "Page";
    var headerCell2 = document.createElement("th");
    headerCell2.textContent = "Count";
    var headerCell3 = document.createElement("th");
    headerCell3.textContent = "Skipped";

    headerRow.appendChild(headerCell1);
    headerRow.appendChild(headerCell2);
    headerRow.appendChild(headerCell3);

    tableHeader.appendChild(headerRow);
    table.appendChild(tableHeader);

    // Iterate over the reports and create table rows
    for (var page in reportData.reports.pages) {
        var row = document.createElement("tr");
        var cell1 = document.createElement("td");
        cell1.textContent = page;
        var cell2 = document.createElement("td");
        cell2.textContent = reportData.reports.pages[page].count;
        var cell3 = document.createElement("td");
        cell3.textContent = reportData.reports.pages[page].skiped;

        row.appendChild(cell1);
        row.appendChild(cell2);
        row.appendChild(cell3);

        tableBody.appendChild(row);
    }

    // Create a row for the "Total" count
    var totalRow = document.createElement("tr");
    var totalCell1 = document.createElement("td");
    totalCell1.innerHTML = "<strong>Total</strong>";
    var totalCell2 = document.createElement("td");
    totalCell2.innerHTML = "<strong>" + reportData.reports.total + "</strong>";
    var totalCell3 = document.createElement("td");
    totalCell3.textContent = ""; // Leave the "Skipped" column empty for the total row

    totalRow.appendChild(totalCell1);
    totalRow.appendChild(totalCell2);
    totalRow.appendChild(totalCell3);

    tableBody.appendChild(totalRow);

    table.appendChild(tableBody);

    // Append elements to the report div
    reportDiv.appendChild(heading);
    reportDiv.appendChild(message);
    reportDiv.appendChild(table);

    return "<hr>" + reportDiv.outerHTML;
}


async function updateCsvFile() {
    let updBtn = jQuery(".update-csvData");
    let exHtm = updBtn.html();
    updBtn.html('...');

    let strCsv = '';

    currentCsvData.forEach((elm) => {
        elm = elm.filter(function (el) {
            return el != null;
        });
        if (elm.length > 0) {
            strCsv += '"' + elm.join('", "') + '"\n';
        }
    });
    await jQuery.post(ADMIN_URL + "/sitemap/csv-update/", {
        name: loadedCsvFile,
        val: strCsv
    }, (response) => {
        response = JSON.parse(response);
        updBtn.html(exHtm);
        if (!response.error) {
            console.log('File Updated')
        }
    });
}


function uploadCsvFile2Server(_this) {
    let files = _this.files;
    let file = files[0];
    //console.log(file);
    const $ = jQuery;
    const data = new FormData();
    if (file.type !== 'application/vnd.ms-excel') {
        alert('File Type must be (*.csv)');
        return;
    }
    data.append('file', file); // append all files
    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = ((evt.loaded / evt.total) * 100);
                    $(".csv-progress").width(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        type: 'POST',
        url: ADMIN_URL + "/sitemap/csv-upload/",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $(".csv-progress").width('0%');
        },
        error: function () {
            //$('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
        },
        success: function (resp) {
            resp = JSON.parse(resp);
            console.log(resp.fname);
            if (!resp.error) {
                $(".svgFileList").prepend(`<li data-name='${resp.fname}' class='csvList' onclick='loadCsv(this)'><span class='removeList' onclick='removeCsv(this)'>&times;</span>${resp.fname}</li>`)
            } else {
                console.log('upload Error');
            }
        }
    });
}

//**
let loadedCsvFile = false;
let currentCsvData = [];
let activeInput = false;
let selStart = false;

function VisualizeCsv(data, quoteChar = '"', delimiter = ',') {
    var rows = data.split("\n");
    const regex = new RegExp(`\\s*(${quoteChar})?(.*?)\\1\\s*(?:${delimiter}|$)`, 'gs');
    const match = line => [...line.matchAll(regex)]
        .map(m => m[2])
        .slice(0, -1);
    //Dom=
    let table = document.createElement('table');
    if (Array.isArray(rows)) {
        let r = 0;
        let rs = [];
        rows.map(line => {

            let tr = document.createElement('tr');
            let c = 0;
            let cl = [];
            let cnt = document.createElement('td');
            cnt.innerHTML = `<span class="csv-row-count">${(r + 1)}</span>`;
            tr.appendChild(cnt);
            match(line).reduce((acc, cur, i) => {
                const val = cur.length <= 0 ? null : Number(cur) || cur;
                cl.push(val);
                let td = document.createElement('td');
                let dd = document.createElement('span');

                dd.innerHTML = val;
                dd.setAttribute('title', val);
                dd.classList.add('dataPlaceHolder');
                dd.setAttribute('data-address', r + ":" + c);
                dd.setAttribute('contenteditable', 'true');
                dd.addEventListener('keyup', (e) => {
                    jQuery('.update-csvData').css('display', 'flex');
                    let elm = e.target;
                    let modVal = e.target.innerHTML;
                    let address = elm.getAttribute('data-address');
                    let addresPart = address.split(":");
                    currentCsvData[addresPart[0]][addresPart[1]] = modVal;
                    //console.log(currentCsvData);//
                });
                td.appendChild(dd);
                tr.appendChild(td);
                c++;
            }, {});
            rs.push(cl);
            table.appendChild(tr);
            r++;
        });
        currentCsvData = rs;
    }
    document.querySelector('.svg-data').innerHTML = "";
    document.querySelector('.svg-data').appendChild(table);
}


async function loadCsv(_this) {
    //Remove Active class
    jQuery(".svg-data").html('<span class="loading-data">Loading Data...</span>');

    jQuery(".svgFileList li").removeClass('active');
    jQuery(".svg-data").css('opacity', '.3');
    //Set Active class
    jQuery(_this).addClass('active');
    //request for data   
    let filename = jQuery(_this).attr('data-name');
    await jQuery.post(ADMIN_URL + '/sitemap/csv-data/', {
        name: filename
    }, (response) => {
        loadedCsvFile = filename;
        jQuery(".svg-data").css('opacity', '1');
        VisualizeCsv(response);
        //data Visualize
    });
}


async function generateSitemap(_this) {
    _this.innerHTML = 'Generating...';
    await jQuery.get(ADMIN_URL + '/sitemap/generate/', response => {
        response = JSON.parse(response);
        if (response.error) {
            _this.innerHTML = 'Error generating ';
            jQuery("#gerenateResponse").html(response.message);
            jQuery("#gerenateResponse").css('color', '#f00');
        } else {
            _this.innerHTML = 'Generated Successfully';
            jQuery("#viewUrl").attr('href', response.file);
            jQuery("#gerenateResponse").html(response.message);
            jQuery("#gerenateResponse").css('color', 'green');

            var reportHTML = generateReportHTML(response);

            // Assuming you have a div with the id "reportContainer" in your HTML
            document.getElementById("reportContainer").innerHTML = reportHTML;

        }
        console.log(response);
    });
}