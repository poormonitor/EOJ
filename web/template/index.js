window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());
gtag('config', 'UA-175545655-2');

$(document).ready(function() {
    $("form").append("<div id='csrf' />");
});

$(".hint pre").each(function() {
    var plus = "<span class='glyphicon glyphicon-plus'>Click</span>";
    var content = $(this);
    $(this).before(plus);
    $(this).prev().click(function() {
        content.toggle();
    });

});

function admin_mod() {
    $("div[fd=source]").each(function() {
        let pid = $(this).attr('pid');
        $(this).append("<span><label class='label label-success' pid='" + pid + "' onclick='problem_add_source(this," + pid + ");'>+</label></span>");

    });
    $("span[fd=time_limit]").each(function() {
        let sp = $(this);
        let pid = $(this).attr('pid');
        $(this).dblclick(function() {
            let time = sp.text();
            console.log("pid:" + pid + "  time_limit:" + time);
            sp.html("<form onsubmit='return false;'><input type=hidden name='m' value='problem_update_time'><input type='hidden' name='pid' value='" + pid + "'><input type='text' name='t' value='" + time + "' selected='true' class='input-mini' size=2 ></form>");
            let ipt = sp.find("input[name=t]");
            ipt.focus();
            ipt[0].select();
            sp.find("input").change(function() {
                let newtime = sp.find("input[name=t]").val();
                $.post("admin/ajax.php", sp.find("form").serialize()).done(function() {
                    console.log("new time_limit:" + time);
                    sp.html(newtime);
                });

            });
        });

    });
}

function problem_add_source(sp, pid) {
    console.log("pid:" + pid);
    let p = $(sp).parent();
    p.html("<form onsubmit='return false;'><input type='hidden' name='m' value='problem_add_source'><input type='hidden' name='pid' value='" + pid + "'><input type='text' class='input input-large' name='ns'></form>");
    p.find("input").focus();
    p.find("input").change(function() {
        console.log(p.find("form").serialize());
        let ns = p.find("input[name=ns]").val();
        console.log("new source:" + ns);
        $.post("admin/ajax.php", p.find("form").serialize());
        p.parent().append("<span class='label label-success'>" + ns + "</span>");
        p.html("<span class='label label-success' pid='" + pid + "' onclick='problem_add_source(this," + pid + ");'>+</span>");
    });
}

function vcode_required(self) {
    if ($("input#vcode").length == 0) {
        return true;
    }
    content = document.createElement('div');
    content.setAttribute('class', 'row')
    content.setAttribute('onsubmit', 'return set_val(this)')
    content.setAttribute('style', 'padding:15px;')
    content.innerHTML = "<div class='col-xs-8'><input name='vcode' id='vcode-input' class='form-control' type='text' required autofocus></div><div class='col-xs-4'><img id='vcode-img' alt='click to change' src='vcode.php?' + Math.random() + '' onclick='change_vcode(this)' height=auto autocomplete='off'>\</div>";
    swal({
        title: "验证码",
        content: content
    }).then((onConfirm) => {
        value = $("#vcode-input").val();
        $("#vcode").val(value);
        self.submit();
    });
    return false;
}

function change_vcode(self) {
    self.attr('src', 'vcode.php?' + Math.random())
    return true;
}

function CopyToClipboard(input) {
    var textToClipboard = input;

    var success = true;
    if (window.clipboardData) { // Internet Explorer
        window.clipboardData.setData("Text", textToClipboard);
    } else {
        // create a temporary element for the execCommand method
        var forExecElement = CreateElementForExecCommand(textToClipboard);

        /* Select the contents of the element 
        (the execCommand for 'copy' method works on the selection) */
        SelectContent(forExecElement);

        var supported = true;

        // UniversalXPConnect privilege is required for clipboard access in Firefox
        try {
            if (window.netscape && netscape.security) {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }

            // Copy the selected content to the clipboard
            // Works in Firefox and in Safari before version 5
            success = document.execCommand("copy", false, null);
        } catch (e) {
            success = false;
        }

        // remove the temporary element
        document.body.removeChild(forExecElement);
    }

    if (success) {
        swal({
            text: "复制成功！",
            timer: 1000
        });
    } else {
        swal({
            text: "浏览器不允许网页使用剪切板！",
            timer: 1000
        });
    }
}

function CreateElementForExecCommand(textToClipboard) {
    var forExecElement = document.createElement("pre");
    // place outside the visible area
    forExecElement.style.position = "absolute";
    forExecElement.style.left = "-10000px";
    forExecElement.style.top = "-10000px";
    // write the necessary text into the element and append to the document
    forExecElement.textContent = textToClipboard;
    document.body.appendChild(forExecElement);
    // the contentEditable mode is necessary for the  execCommand method in Firefox
    forExecElement.contentEditable = true;

    return forExecElement;
}

function SelectContent(element) {
    // first create a range
    var rangeToSelect = document.createRange();
    rangeToSelect.selectNodeContents(element);

    // select the contents
    var selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(rangeToSelect);
}

function getTotal(rows) {
    var total = 0;
    for (var i = 0; i < rows.length && total == 0; i++) {
        try {
            total = parseInt(rows[rows.length - i].cells[0].innerHTML);
            if (isNaN(total))
                total = 0;
        } catch (e) {}
    }
    return total;
}

function clock() {
    var x, h, m, s, n, xingqi, y, mon, d;
    var x = new Date(new Date().getTime() + diff);
    y = x.getYear() + 1900;

    if (y > 3000)
        y -= 1900;

    mon = x.getMonth() + 1;
    d = x.getDate();
    xingqi = x.getDay();
    h = x.getHours();
    m = x.getMinutes();
    s = x.getSeconds();
    n = y + "-" + (mon >= 10 ? mon : "0" + mon) + "-" + (d >= 10 ? d : "0" + d) + " " + (h >= 10 ? h : "0" + h) + ":" + (m >= 10 ? m : "0" + m) + ":" + (s >= 10 ? s : "0" + s);

    document.getElementById('nowdate').innerHTML = n;
    setTimeout("clock()", 1000);
}

function checkFileList(files) {
    if (typeof window.FileReader !== 'function')
        error_msg("The file API isn't supported on this browser yet.");

    if (files.length > 0) readFile(files[0], "lhs");
    if (files.length > 1) readFile(files[1], "rhs");
}

function readFile(file, side) {
    var reader = new FileReader();
    reader.onload = function file_onload() {
        // document.getElementById('td1').innerHTML = ..
        $('#path-' + side).text(file.name);
        $('#compare').mergely(side, reader.result);
    }
    reader.readAsBinaryString(file);

}

function handleDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
}

function handleFileSelect(evt) {
    document.getElementById('drop_zone').visibility = "collapse";
    evt.stopPropagation();
    evt.preventDefault();
    var files = evt.dataTransfer.files; // FileList object.
    checkFileList(files);
}

function download_content(a, side) {
    //a.innerHTML = "preparing content..";
    var txt = $('#compare').mergely('get', side);
    var datauri = "data:plain/text;charset=UTF-8," + encodeURIComponent(txt);
    a.setAttribute('download', side + ".txt");
    a.setAttribute('href', datauri);
    //a.innerHTML = "content ready.";
}

function print_result(solution_id) {
    sid = solution_id;
    $.get("status-ajax.php?tr=1&solution_id=" + solution_id, function(data, status) {
        $("#out").text(data)
    })
    swal("测试结束");
}

function fresh_test_result(solution_id) {
    var tb = window.document.getElementById('result');
    switch (solution_id) {
        case "-1":
            swal("$MSG_VCODE_WRONG！");
            tb.innerHTML = "状态";
            if ($("#vcode") != null) $("#vcode").click();
            return;
        case "-2":
            swal("您的代码不符合填空格式！");
            tb.innerHTML = "状态";
            if ($("#vcode") != null) $("#vcode").click();
            return;
        case "-3":
            swal("代码中有禁用的关键词或没有使用必须的关键词！");
            tb.innerHTML = "状态";
            if ($("#vcode") != null) $("#vcode").click();
            return;
    }

    sid = solution_id;
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var r = xmlhttp.responseText;
            var ra = r.split(",");
            //swal(r);
            // swal(judge_result[r]);
            var loader = "<img width=18 style='margin-left:3px;' src='image/loading.gif'>";
            var tag = "span";

            if (ra[0] < 4)
                tag = "span disabled=true";
            else {
                if (ra[0] == 11)
                    tb.innerHTML = "<a href='ceinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[ra[0]] + "</a>";
                else
                    tb.innerHTML = "<a href='reinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[ra[0]] + "</a>";
            }

            if (ra[0] < 4) tb.innerHTML += loader;

            tb.innerHTML = "Memory:" + ra[1];
            tb.innerHTML += ", Time:" + ra[2];

            if (ra[0] < 4)
                window.setTimeout("fresh_test_result(" + solution_id + ")", 2000);
            else {
                window.setTimeout("print_result(" + solution_id + ")", 2000);
                count = 1;
            }
        }
    }

    xmlhttp.open("GET", "status-ajax.php?solution_id=" + solution_id, true);
    xmlhttp.send();
}

function getSID() {
    var ofrm1 = document.getElementById("testRun").document;
    var ret = "0";
    if (ofrm1 == undefined) {
        ofrm1 = document.getElementById("testRun").contentWindow.document;
        var ff = ofrm1;
        ret = ff.innerHTML;
    } else {
        var ie = document.frames["frame1"].document;
        ret = ie.innerText;
    }
    return ret + "";
}

var count = 0;

var handler_interval;

function do_test_run() {
    if (handler_interval) window.clearInterval(handler_interval);
    if ($("input#vcode_input").val() == "") {
        swal("验证码空！");
        return false;
    }
    var loader = "<img width=18 style='margin-left:3px;' src='" + OJ_CDN + "image/loading.gif'>";
    var tb = window.document.getElementById('result');
    var source = $("#source").val();

    if (typeof(editor) != "undefined") {
        source = editor.getValue();
        $("#hide_source").val(source);
    }
    if (source.length < 10) return swal("代码过短!");

    if (tb != null) tb.innerHTML = loader;

    var problem_id = document.getElementById(mark);
    problem_id.value = -problem_id.value;
    document.getElementById("frmSolution").target = "testRun";
    //$("#hide_source").val(editor.getValue());
    //document.getElementById("frmSolution").submit();
    $.post("submit.php?ajax", $("#frmSolution").serialize(), function(data) {
        fresh_test_result(data);
    });
    $("#Submit").prop('disabled', true);
    $("#TestRun").prop('disabled', true);
    problem_id.value = -problem_id.value;
    count = 20;
    handler_interval = window.setTimeout("resume();", 1000);
}

function switchLang(lang) {
    var langnames = new Array("c_cpp", "c_cpp", "pascal", "java", "ruby", "sh", "python", "php", "perl", "csharp", "objectivec", "vbscript", "scheme", "c_cpp", "c_cpp", "lua", "javascript", "golang");
    editor.getSession().setMode("ace/mode/" + langnames[lang]);
}

function switchLangs(lang) {
    var langnames = new Array("c_cpp", "c_cpp", "pascal", "java", "ruby", "sh", "python", "php", "perl", "csharp", "objectivec", "vbscript", "scheme", "c_cpp", "c_cpp", "lua", "javascript", "golang");
    editors.forEach(function(elem) {
        elem.getSession().setMode("ace/mode/" + langnames[lang])
    });
}

function replay() {
    replay_index = 0;
    window.setTimeout("add()", 1000);
}

function sec2str(sec) {
    var ret = "";
    if (sec < 36000) ret = "0";
    ret += parseInt(sec / 3600);
    ret += ":";
    if (sec % 3600 / 60 < 10) ret += "0";
    ret += parseInt(sec % 3600 / 60);
    ret += ":";
    if (sec % 60 < 10) ret += "0";
    ret += parseInt(sec % 60);
    return ret;
}

function str2sec(str) {
    var s = str.split(":");
    var h = parseInt(s[0]);
    var m = parseInt(s[1]);
    var s = parseInt(s[2]);
    return h * 3600 + m * 60 + s;
}

function colorful(td, ac, num) {
    if (num < 0) num = -num;
    else num = 0;
    num *= 10
    if (num > 255) num = 255;
    if (ac && num > 200) num = 200;
    var rb = ac ? num : 255 - num;
    if (ac) {
        //	td.className="well green";
        td.style = "background-color: rgb(" + rb + ",255," + rb + ");";
    } else {
        td.style = "background-color: rgb(255," + rb + "," + rb + ");";
    }
}

function update(tab, row, solution) {
    var col = parseInt(solution["num"]) + 5;
    var old = row.cells[col].innerHTML;
    var time = 0;
    if (old != "") time = parseInt(old);
    if (!(old.charAt(0) == '-' || old == '')) return;
    if (parseInt(solution["result"]) == 4) {
        if (old.charAt(0) == '-' || old == '') {
            var pt = time;
            time = parseInt(solution["in_date"]) - time * 1200;

            penalty = str2sec(row.cells[4].innerHTML);
            penalty += time;
            row.cells[4].innerHTML = sec2str(penalty);
            row.cells[col].innerHTML = sec2str(parseInt(solution["in_date"]));
            if (pt != 0)
                row.cells[col].innerHTML += "(" + pt + ")";
            colorful(row.cells[col], true, pt);
        } else {
            if (row.cells[col].className == "well green");
        }
        row.cells[3].innerHTML = parseInt(row.cells[3].innerHTML) + 1;
    } else {
        time--;
        row.cells[col].innerHTML = time;
        colorful(row.cells[col], false, time);
    }
    /*
if(parseInt(solution["result"])==4){
   if(row.cells[col].className!="well green"){
  }
  row.cells[col].className="well green";
}else{
   if(row.cells[col].className!="well green") 
      row.cells[col].className="well red";
}
*/
}

function sort(rows) {
    for (var i = 1; i < rows.length; i++) {
        for (var j = 1; j < i; j++) {
            if (cmp(rows[i], rows[j])) {
                swapNode(rows[i], rows[j]);
            }
        }

    }

}

function swapNode(node1, node2) {
    var parent = node1.parentNode; //父节点
    var t1 = node1.nextSibling; //两节点的相对位置
    var t2 = node2.nextSibling;
    $(node1).fadeToggle("slow");
    $(node2).fadeToggle("slow");
    //如果是插入到最后就用appendChild
    if (t1) parent.insertBefore(node2, t1);
    else parent.appendChild(node2);
    if (t2) parent.insertBefore(node1, t2);
    else parent.appendChild(node1);
    $(node1).fadeToggle("slow");
    $(node2).fadeToggle("slow");
}

function cmp(a, b) {
    if (parseInt(a.cells[3].innerHTML) > parseInt(b.cells[3].innerHTML))
        return true;

    if (parseInt(a.cells[3].innerHTML) == parseInt(b.cells[3].innerHTML))
        return str2sec(a.cells[4].innerHTML) < str2sec(b.cells[4].innerHTML);
}

function trim(str) { //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

function newrow(tab, solution) {

    var row = "<tr><td></td><td>" + solution['user_id'] + "</td>";
    row += "<td>" + trim(solution['nick']) + "</td>";
    row += "<td>";
    var css = "grey";
    var time = 0;
    if (solution['result'] == 4) {
        row += "1";
        time = solution['in_date'];
        count = sec2str(time);
        css = "well green";
    } else {
        row += "0";
        css = "well red";
        count = -1;
    }
    row += "</td>";
    var n = tab[0].rows[0].cells.length;
    row += "<td>" + sec2str(time) + "</td>";

    for (var i = 5; i < n; i++) {
        if (i - 5 == solution['num'])
            row += "<td class='" + css + "'>" + count + "</td>";
        else
            row += "<td></td>";
    }
    row += "</tr>";
    return row;
}

function findrow(tab, solution) {
    var rows = tab[0].rows;
    for (var i = 0; i < rows.length; i++) {
        if (rows[i].cells[1].innerText == solution['user_id'])
            return rows[i];
    }
    return null;
}

function explain() {
    var errmsg = $("#errtxt").text();
    var expmsg = "";
    for (var i = 0; i < pats.length; i++) {
        var pat = pats[i];
        var exp = exps[i];
        var ret = pat.exec(errmsg);
        if (ret) {
            expmsg += ret + " : " + exp + "<br /><hr />";
        }
    }
    document.getElementById("errexp").innerHTML = expmsg;
}

function showDownload() {
    var errmsg = $("#errtxt").html();
    errmsg = errmsg.replace(/========\[(.*)\]=========/g, "<a href='download.php?sid=<?php echo $id?>&name=$1'>$1</a>");
    $("#errtxt").html(errmsg);
}

function auto_refresh() {
    interval = 800;
    var tb = window.document.getElementById('result-tab');
    var rows = tb.rows;
    for (var i = rows.length - 1; i > 0; i--) {
        var result = $(rows[i].cells[3].children[0]).attr("result");
        rows[i].cells[3].className = "td_result";
        var sid = rows[i].cells[0].innerText;
        if (result < 4) {
            window.setTimeout("fresh_result(" + sid + ")", interval);
            console.log("auto_refresh " + sid + " actived!");
            break;
        }
    }
}

function findRow(solution_id) {
    var tb = window.document.getElementById('result-tab');
    var rows = tb.rows;
    for (var i = 1; i < rows.length; i++) {
        var cell = rows[i].cells[0];
        if (cell.innerText == solution_id)
            return rows[i];
    }
}

function fresh_result(solution_id) {
    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var tb = window.document.getElementById('result-tab');
            var row = findRow(solution_id);
            //alert(row);
            var r = xmlhttp.responseText;
            var ra = r.split(",");
            ra[0] = parseInt(ra[0]);
            // alert(r);
            // alert(judge_result[r]);
            var loader = "<img width=18 style='margin-left:3px;' src='" + OJ_CDN + "image/loading.gif'>";
            row.cells[4].innerHTML = ra[1];
            row.cells[5].innerHTML = ra[2];

            if (ra[3] != "none")
                row.cells[9].innerHTML = ra[3];

            if (ra[0] < 4) {
                //console.log(loader);
                if (-1 == row.cells[3].innerHTML.indexOf("loading")) {
                    //console.log(row.cells[3].innerHTML);
                    row.cells[3].innerHTML += loader;
                }
                interval *= 1.5;
                window.setTimeout("fresh_result(" + solution_id + ")", interval);
            } else {
                //console.log(ra[0]);
                switch (ra[0]) {
                    case 4:
                        row.cells[3].innerHTML = "<a href=reinfo.php?sid=" + solution_id + " class='" + judge_color[ra[0]] + "'>" + judge_result[ra[0]] + "</a>";
                        break;
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                    case 10:
                        row.cells[3].innerHTML = "<a href=reinfo.php?sid=" + solution_id + " class='" + judge_color[ra[0]] + "'>" + judge_result[ra[0]] + " AC:" + ra[4].trim() + "%</a>";
                        break;
                    case 11:
                        row.cells[3].innerHTML = "<a href=ceinfo.php?sid=" + solution_id + " class='" + judge_color[ra[0]] + "'>" + judge_result[ra[0]] + "</a>";
                        break;
                    default:
                        //						row.cells[3].innerHTML = "<span class='"+judge_color[ra[0]]+"'>"+judge_result[ra[0]]+" AC:"+ra[4].trim()+"%</span>";
                }

                auto_refresh();
            }
        }
    }
    xmlhttp.open("GET", "status-ajax.php?solution_id=" + solution_id, true);
    xmlhttp.send();
}

function http_judge(btn) {
    var sid = $(btn).parent()[0].children[0].value;
    $.post("admin/problem_judge.php", $(btn).parent().serialize(), function(data, textStatus) {
        if (textStatus == "success") {
            window.setTimeout("fresh_result(" + sid + ")", 1000)
        };
    })
    return false;
}