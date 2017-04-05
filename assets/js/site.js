var index = 1;
function test() {
  var target = document.getElementById("raaka_aine_valinnat");
  var e = document.getElementById("mitta_yksikko");
  var yksikko = e.options[e.selectedIndex].text;
  var r=$('<div class="form-group" id="rivi'+index+'"><div class="col-sm-6"><input class="form-control" type="text" name="resepti_raaka_aineet[]" value="'+document.getElementById("raaka_aine").value+'" disabled></div><div class="col-sm-3"><input class="form-control" type="text" value="'+ document.getElementById("maara").value +'" name="maarat[]" disabled></div><div class="col-sm-2"><input class="form-control" type="text" name="mitta_yksikko[]" value="'+yksikko+'" disabled></input></div><div class="col-sm-1"><i class="fa fa-pencil fa-2x" aria-hidden="true"></i> <a onclick="sulje(rivi'+index+')" class="sulje"><i class="fa fa-times fa-2x redicon" aria-hidden="true"></i></a></div></div>');

  $(target).append(r);
  index++;
  document.getElementById("raaka_aine").value = "";
  document.getElementById("maara").value = "";
  document.getElementById("mitta_yksikko").value = 0;
}
