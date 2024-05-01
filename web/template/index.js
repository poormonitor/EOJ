var langString = {
    "zh": {
        Vcode: "验证码",
        Copied: "复制成功",
        CopyForbidden: "浏览器不允许网页使用剪切板",
        TestRunOver: "测试运行结束",
        Status: "状态",
        VcodeWrong: "验证码错误",
        FormatWrong: "您的代码不符合填空格式",
        KeywordWrong: "代码中有禁用的关键词或没有使用必须的关键词",
        TimeKeyword: ["天", "小时", "分", "秒"],
    }, "en":
    {
        Vcode: "Verification Code",
        Copied: "Copied!",
        CopyForbidden: "The broswer does not allowed clipboard actions.",
        TestRunOver: "Test run finished.",
        Status: "Status",
        VcodeWrong: "Verification code is wrong.",
        FormatWrong: "The code does not match the template.",
        KeywordWrong: "Required keywords are not used in the code or Banned ones are used.",
        TimeKeyword: ["days", "hours", "minutes", "seconds"],
    }
}[OJ_LANG];

var language_monaco = ["cpp", "cpp", "pascal", "java", "ruby", "shell", "python", "php", "perl", "csharp", "objective-c", "vb", "scheme", "cpp", "cpp", "lua", "javascript", "go", "sql", "plain", "plain", "plain"];

var notyf = new Notyf();

var isDarkMode = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
    || ("querySelector" in document && !!document.querySelector("meta[name=darkreader]"));

$(document).ready(function () {
    $("form").append("<div id='csrf' />");
});

$(".hint pre").each(function () {
    var plus = "<span class='glyphicon glyphicon-plus'>Click</span>";
    var content = $(this);
    $(this).before(plus);
    $(this).prev().click(function () {
        content.toggle();
    });

});

$("table.ud-margin").each(function (_, elem) {
    table = $(elem);
    if (!table.parent().hasClass("table-responsive")) {
        $(elem).wrap("<div class='table-responsive'></div>");
    }
})

function create_mce(students = false) {
    if (students) {
        $("textarea[id^='tinymce']").each(function (index, _) {
            tinymce.init({
                selector: "#tinymce" + index,
                language: 'zh_CN',
                inline: false,
                plugins: 'image paste',
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | paste image',
                height: 400,
                fontsize_formats: '14px',
                extended_valid_elements: [
                    'img[class=ud-margin|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]',
                    'table[class=ud-margin|border|cellspacing|cellpadding|align|summary|bgcolor|width|height|style]'
                ],
                template_cdate_format: '[CDATE: %m/%d/%Y : %H:%M:%S]',
                template_mdate_format: '[MDATE: %m/%d/%Y : %H:%M:%S]',
                autosave_ask_before_unload: false,
                toolbar_mode: 'wrap',
                paste_remove_styles_if_webkit: true,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        this.getBody().style.fontSize = '14px';
                    });
                },
                relative_urls: false,
                paste_data_images: true,
                images_upload_handler: function (blobInfo, success, failure, progress) {
                    var xhr, formData;
                    var file = blobInfo.blob(); //转化为易于理解的file对象
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '/tinymce/upimgs.php');
                    xhr.upload.onprogress = function (e) {
                        progress(e.loaded / e.total * 100);
                    };
                    xhr.onload = function () {
                        var json;
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };
                    formData = new FormData();
                    formData.append('uploadkey', uploadkey)
                    formData.append('file', file, file.name); //此处与源文档不一样
                    xhr.send(formData);
                }
            });
        })
    } else {
        $("textarea[id^='tinymce']").each(function (index, _) {
            tinymce.init({
                selector: "#tinymce" + index,
                language: 'zh_CN',
                inline: false,
                plugins: 'paste print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media \
                        template codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount \
                        textpattern help emoticons autosave autoresize mathjax code',
                toolbar: 'code undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough anchor | alignleft aligncenter alignright alignjustify outdent indent | \
                         formatselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
                         table image media charmap emoticons hr pagebreak insertdatetime print preview | fullscreen | lineheight link mathjax',
                height: 650,
                min_height: 400,
                fontsize_formats: '14px 24px',
                extended_valid_elements: [
                    'img[class=ud-margin|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]',
                    'table[class=ud-margin|border|cellspacing|cellpadding|align|summary|bgcolor|width|height|style]'
                ],
                mathjax: {
                    lib: OJ_CDN + "tinymce/plugins/mathjax/tex-mml-chtml.js",
                    symbols: {
                        start: '$',
                        end: '$'
                    }
                },
                template_cdate_format: '[CDATE: %m/%d/%Y : %H:%M:%S]',
                template_mdate_format: '[MDATE: %m/%d/%Y : %H:%M:%S]',
                autosave_ask_before_unload: false,
                toolbar_mode: 'wrap',
                paste_remove_styles_if_webkit: true,
                setup: function (editor) {
                    editor.on('init', function (e) {
                        this.getBody().style.fontSize = '14px';
                    });
                },
                relative_urls: false,
                file_picker_callback: function (callback, _, meta) {
                    var filetype = '.*';
                    var upurl = '/tinymce/upfile.php';
                    switch (meta.filetype) {
                        case 'image':
                            filetype = '.jpg, .jpeg, .png, .gif, .bmp';
                            upurl = '/tinymce/upimg.php';
                            break;
                        case 'media':
                            filetype = '.mp3, .flac, .aac, .wav, .mp4, .mkv, .wmv, .avi, .flv';
                            break;
                        default:
                            break;
                    }
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', filetype);
                    input.click();
                    input.onchange = function () {
                        var file = this.files[0];
                        var xhr, formData;
                        console.log(file.name);
                        xhr = new XMLHttpRequest();
                        xhr.withCredentials = false;
                        xhr.open('POST', upurl);
                        xhr.onload = function () {
                            var json;
                            if (xhr.status != 200) {
                                failure('HTTP Error: ' + xhr.status);
                                return;
                            }
                            json = JSON.parse(xhr.responseText);
                            if (!json || typeof json.location != 'string') {
                                failure('Invalid JSON: ' + xhr.responseText);
                                return;
                            }
                            callback(json.location, {
                                text: file.name
                            });
                        };
                        formData = new FormData();
                        formData.append('file', file, file.name);
                        formData.append('uploadkey', uploadkey)
                        xhr.send(formData);
                    }
                }
            });
        })
    }
}

function vcode_required(self) {
    if ($("input#vcode").length == 0) {
        return true;
    }
    content = document.createElement('div');
    content.setAttribute('class', 'row')
    content.setAttribute('onsubmit', 'return set_val(this)')
    content.setAttribute('style', 'padding:15px;')
    HTMLcontent = "<div class='col-xs-8'><input name='vcode' id='vcode-input' class='form-control' type='text' required autofocus></div>";
    HTMLcontent += "<div class='col-xs-4'><img id='vcode-img' alt='Click to change' src='vcode.php?" + Math.random() + " onclick='change_vcode(this)' height=auto autocomplete='off'></div>"
    content.innerHTML = HTMLcontent;
    swal({
        title: langString.Vcode,
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
        notyf.success(langString.Copied);
    } else {
        notyf.error(langString.CopyForbidden);
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
        } catch (e) { }
    }
    return total;
}

function clock() {
    var x, h, m, s, n, w, y, mon, d;
    var x = new Date(new Date().getTime() + diff);
    y = x.getYear() + 1900;

    if (y > 3000)
        y -= 1900;

    mon = x.getMonth() + 1;
    d = x.getDate();
    w = x.getDay();
    h = x.getHours();
    m = x.getMinutes();
    s = x.getSeconds();
    n = y + "-" + (mon >= 10 ? mon : "0" + mon) + "-" + (d >= 10 ? d : "0" + d) + " " + (h >= 10 ? h : "0" + h) + ":" + (m >= 10 ? m : "0" + m) + ":" + (s >= 10 ? s : "0" + s);

    getLeftTime();
    document.getElementById('nowdate').innerHTML = n;
    setTimeout("clock()", 1000);
}

function padNumber(num, fill) {
    var len = ('' + num).length;
    return Array(fill > len ? fill - len + 1 || 0 : 0).join(0) + num;
}

function getLeftTime() {
    $(".time-left").each(function (i, e) {
        var showOnScreen = $(e).text().match(/([\d]+)/g)
        var finalTime = TimeMinusOne(showOnScreen);
        var finalShowTime = ""
        for (var i = 0; i < finalTime.length; i++) {
            index = finalTime.length - 1 - i
            indexKeyword = langString.TimeKeyword.length - 1 - i
            finalShowTime = finalTime[index] + " " + langString.TimeKeyword[indexKeyword]
                + " " + finalShowTime
        }
        $(e).text(finalShowTime)
    })
}

function TimeMinusOne(time) {
    var newTime = time;
    if (newTime.length == 1 && newTime[0] == 0)
        return newTime
    for (var i = newTime.length - 1; i >= 0; i--) {
        if (newTime[i] > 0) {
            newTime[i] = padNumber(newTime[i] - 1, 2)
            break
        } else if (i != 0) {
            newTime[i] = 59;
        }
    }
    if (newTime[0] == 0)
        newTime = newTime.splice(0, 1)
    return newTime;
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

function download_content(a, side, id) {
    if (side == "rhs") {
        var txt = window.diffEditor.getModifiedEditor().getValue();
    } else {
        var txt = window.diffEditor.getOriginalEditor().getValue();
    }
    var datauri = "data:plain/text;charset=UTF-8," + encodeURIComponent(txt);
    a.setAttribute('download', id + ".txt");
    a.setAttribute('href', datauri);
}

function print_result(solution_id) {
    sid = solution_id;
    $.get("status-ajax.php?tr=1&solution_id=" + solution_id, function (data, status) {
        $("#out").text(data)
    })
    notyf.success(langString.TestRunOver)
}

function fresh_test_result(solution_id) {
    var tb = window.document.getElementById('result');
    switch (solution_id) {
        case "-1":
            notyf.error(langString.VcodeWrong)
            tb.innerHTML = langString.Status;
            if ($("#vcode") != null) $("#vcode").click();
            return;
        case "-2":
            notyf.error(langString.FormatWrong)
            tb.innerHTML = langString.Status;
            if ($("#vcode") != null) $("#vcode").click();
            return;
        case "-3":
            notyf.error(langString.KeywordWrong)
            tb.innerHTML = langString.Status;
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

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var r = xmlhttp.responseText;
            var ra = r.split(",");
            //swal(r);
            // swal(judge_result[r]);
            var loader = "<img width=18 style='margin:0 3px;' src='image/loading.gif'>";
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
    var loader = "<img width=18 style='margin:0 3px;' src='" + OJ_CDN + "image/loading.gif'>";
    var tb = window.document.getElementById('result');
    if (tb != null) tb.innerHTML = loader;
    var problem_id = document.getElementById(mark);
    problem_id.value = -problem_id.value;
    document.getElementById("frmSolution").target = "testRun";

    var codeData = $("#frmSolution").serializeArray();
    for (var i = 0; i < codeData.length; i++) {
        if (/((code|multiline)[0-9]?)|(source)/.test(codeData[i].name)) {
            codeData[i].value = encode64(utf16to8(codeData[i].value))
        }
    }

    $.post("submit.php?ajax", $.param(codeData), function (data) {
        fresh_test_result(data);
    });

    $("#Submit").prop('disabled', true);
    $("#TestRun").prop('disabled', true);
    problem_id.value = -problem_id.value;
    count = 20;
    handler_interval = window.setTimeout("resume();", 1000);
}

function get_full_code() {
    var template = $("pre#copy-blank").text();
    $("input[name^=code]").each(function (_, elem) {
        template = template.replace("%*%", elem.value);
    })
    if (typeof window.editors !== 'undefined') {
        for (var i = 0; i < window.editors.length; i++) {
            var length = /\n.*\*%\*/m.exec(template)[0].length;
            var value = window.editors[i].getValue();
            value = value.replace("\n", " ".repeat(length));
            template = template.replace("*%*", value, 1);
        }
    }
    return template;
}

function switchLang(lang) {
    require(['vs/editor/editor.main'], function () {
        monaco.editor.setModelLanguage(window.editor.getModel(), language_monaco[lang])
    });
}

function switchLangs(lang) {
    require(['vs/editor/editor.main'], function () {
        window.editors.forEach(function (elem) {
            monaco.editor.setModelLanguage(elem.getModel(), language_monaco[lang])
        });
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

    xmlhttp.onreadystatechange = function () {
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
    $.post("admin/problem_judge.php", $(btn).parent().serialize(), function (data, textStatus) {
        if (textStatus == "success") {
            window.setTimeout("fresh_result(" + sid + ")", 1000)
        };
    })
    return false;
}

function setAD(url, href, ft) {
    var img = $(document.createElement('img'))
    var ahref = $(document.createElement('a'))
    var float = $(document.createElement('div'))

    img.appendTo(ahref)
    ahref.appendTo(float)
    ahref.attr("href", href)
    ahref.attr("target", "_blank")
    img.attr("id", "floatImg")
    img.attr("src", url)
    img.css("width", "100px")
    float.attr("id", "float")
    float.css("position", "fixed")
    float.css("z-index", "999")
    float.css("display", "none")
    float.appendTo("body")

    var moveX = 0; //X轴方向上移动的距离
    var moveY = 0; //Y轴方向上移动的距离
    var stepX = 1; //图片X轴移动的速度
    var stepY = 1; //图片Y轴移动的速度
    var directionX = 0; //设置图片在X轴方向上的移动方向   0:向右  1:向
    var directionY = 0; //设置图片在Y轴方向上的移动方向   0:向下  1:向上

    function changePos() {
        var img = document.getElementById("float"); //获得图片所在层的ID
        var height = document.documentElement.clientHeight; //浏览器的高度
        var width = document.documentElement.clientWidth; //浏览器的宽度
        var imgHeight = document.getElementById("floatImg").height; //飘浮图片的高度
        var imgWidth = document.getElementById("floatImg").width; //瓢浮图片的宽度
        //设置飘浮图片距离浏览器左侧位置
        img.style.left = parseInt(moveX) + "px";
        //设置飘浮图片距离浏览器右侧位置
        img.style.top = parseInt(moveY) + "px";
        //设置图片在Y轴上的移动规律
        if (directionY == 0) {
            moveY += stepY; //飘浮图片在Y轴方向上向下移动
        } else {
            moveY -= stepY; //飘浮图片在Y轴方向上向上移动
        }
        if (moveY < 0) { //如果飘浮图片飘浮到顶端的时候，设置图片在Y轴方向上向下移动
            directionY = 0;
            moveY = 0;
        }
        if (moveY > (height - imgHeight)) { //如果飘浮图片飘浮到浏览器底端的时候，设置图片在Y轴方向上向上移动
            directionY = 1;
            moveY = (height - imgHeight);
        }
        //设置图片在X轴上的移动规律
        if (directionX == 0) {
            moveX += stepX;
        } else {
            moveX -= stepX;
        }
        if (moveX < 0) { //如果飘浮图片飘浮到浏览器左侧的时候，设置图片在X轴方向上向右移动
            directionX = 0;
            moveX = 0;
        }
        if (moveX > (width - imgWidth)) { //如果飘浮图片飘浮到浏览器右侧的时候，设置图片在X轴方向上向左移
            directionX = 1;
            moveX = (width - imgWidth);
        }
        console.log(moveX, moveY)
        requestAnimationFrame(changePos)
    }

    if (ft) {
        moveX = Math.round(Math.random() * document.documentElement.clientWidth)
        moveY = Math.round(Math.random() * document.documentElement.clientHeight)
        float.css("left", moveX)
        float.css("top", moveY)
        float.css("display", "")
        requestAnimationFrame(changePos)
    } else {
        moveX = 50
        moveY = 50
        float.css("right", moveX)
        float.css("bottom", moveY)
        float.css("display", "")
    }
}