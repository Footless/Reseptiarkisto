var index = 1;
var ohjeIndex = 1;
var inFocus = false;
var taytetty = false;

function teeUusiRaaka_aine() {
  var target = document.getElementById("raaka_aine_valinnat");
  var e = document.getElementById("mitta_yksikko");
  var yksikko = e.options[e.selectedIndex].text;
  var r=$('<div class="form-group" id="raaka_aine'+index+'"><div class="col-sm-6">'+
  '<input class="form-control" type="text" name="resepti_raaka_aineet[]" value="'+document.getElementById("raaka_aine").value+'" required>'+
  '</div><div class="col-sm-3"><input class="form-control" type="text" value="'+ document.getElementById("maara").value +'" name="resepti_maarat[]" required />'+
  '</div><div class="col-sm-2"><input class="form-control hidden" type="text" name="mitta_yksikko[]" value="'+yksikko+'">'+
  '<input class="form-control" type="text" value="'+yksikko+'" disabled></input></div><div class="col-sm-1"><a class="sulje" onClick="sulje(raaka_aine'+index+')">'+
  '<i class="fa fa-times fa-2x redicon" aria-hidden="true"></i></a></div></div>');

  $(target).append(r);
  index++;
  document.getElementById("raaka_aine").value = "";
  document.getElementById("maara").value = "";
  document.getElementById("mitta_yksikko").value = 0;
}

function teeUusiOhje() {
  var ohjeTarget = document.getElementById("ohjeet");
  var x=$('<div class="form-group"><label class="control-label col-sm-1" for="ohje">'+ohjeIndex+'.</label><div class="col-sm-11">'+
  '<textarea class="form-control" name="ohjeet[]">'+document.getElementById("ohje").value+'</textarea></div></div>');
  $(ohjeTarget).append(x);
  ohjeIndex++;
  document.getElementById("ohje").value = "";
}

$('#raaka_aine').focus(function() {
  inFocus = true;
});

$('#raaka_aine').blur(function() {
  inFocus = false;
});

$('#raaka_aine').blur(function()
{
    if( !$(this).val() ) {
          taytetty = false;
    }
});

$('#raaka_aine').blur(function()
{
    if( $(this).val() ) {
          taytetty = true;
    }
});

$('#raaka_aine').blur(function()
{
  if (taytetty && !inFocus) {
    $.ajax({
      type: 'POST',
      url: '/reseptiarkisto/resepti/lisaa-resepti/yksikot/',
      data: {nimi: $('#raaka_aine').val()},
      cache: false,
      success: function(result) {
        if (result) {
          var res = $.parseJSON(result);
          $('.ra_option').remove();
          $.each(res, function( key, value ) {
            $('#mitta_yksikko').append(function() {
              return '<option class="ra_option" value="'+value.kuvaus+'">'+value.kuvaus+'</div>';
            });
          });
        }
      }
    });
  }
  taytetty = false;
});

$(".lisaaSuosikki").click(function() {
    var reseptiId = $(this).attr("id");
    $.ajax({
      type: 'POST',
      url: '/reseptiarkisto/kayttaja/lisaaSuosikki/',
      data: {id: reseptiId},
      cache: false
    });
    $(this).find('i:first').remove();
    $(this).remove();
    var target = document.getElementById("suosikit"+reseptiId);
    $(target).append(function() {
      return '<a class="poistaSuosikki" id="'+ reseptiId +'"><i class="fa fa-star fa-2x room right" aria-hidden="true"></i></a>';
    });
});

$(".poistaSuosikki").click(function() {
    var reseptiId = $(this).attr("id");
    $.ajax({
      type: 'POST',
      url: '/reseptiarkisto/kayttaja/poistaSuosikki/',
      data: {id: reseptiId},
      cache: false,
    });
    var child = $(this).find('i:first').remove();
    $(this).remove();
    var target = document.getElementById("suosikit"+reseptiId);
    $(target).append(function() {
      return '<a class="lisaaSuosikki" id="'+ reseptiId +'"><i class="fa fa-star-o fa-2x room right" aria-hidden="true"></i></a>';
    });
});
