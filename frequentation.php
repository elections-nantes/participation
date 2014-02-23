    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
    <style>
    #map {
      width: 99%;
      height: 500px;
    }

    .active {
        stroke: green;
        stroke-width: 1px;
    }
    .info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255,255,255,0.8);
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 5px;
    }
    .info h4 {
        margin: 0 0 5px;
        color: #777;
    }

    .legend {
        text-align: left;
        line-height: 18px;
        color: #555;
    }
    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
    }
    /* TypeAhead */

    .typeahead,
    .tt-query,
    .tt-hint {
      width: 396px;
      height: 30px;
      padding: 8px 12px;
      font-size: 24px;
      line-height: 30px;
      border: 2px solid #ccc;
      -webkit-border-radius: 8px;
         -moz-border-radius: 8px;
              border-radius: 8px;
      outline: none;
    }

    .typeahead {
      background-color: #fff;
    }

    .typeahead:focus {
      border: 2px solid #0097cf;
    }

    .tt-query {
      -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
         -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
              box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }

    .tt-hint {
      color: #999
    }

    .tt-dropdown-menu {
      width: 422px;
      margin-top: 12px;
      padding: 8px 0;
      background-color: #fff;
      border: 1px solid #ccc;
      border: 1px solid rgba(0, 0, 0, 0.2);
      -webkit-border-radius: 8px;
         -moz-border-radius: 8px;
              border-radius: 8px;
      -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
         -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
              box-shadow: 0 5px 10px rgba(0,0,0,.2);
    }

    .tt-suggestion {
      padding: 3px 20px;
      font-size: 18px;
      line-height: 24px;
    }

    .tt-suggestion.tt-cursor {
      color: #fff;
      background-color: #0097cf;

    }
    </style>

    <div id="sidebar">
        <h5>Ici, vous pouvez consulter le nombre de votants dans votre bureau de vote.</h5>
        <h6>Sélectionnez votre numéro de bureau de vote à l’aide du menu déroulant et consultez sa fréquentation.<br><br>
            Vous pouvez voir l’évolution du nombre d’inscrits depuis les dernières élections municipales.<br><br>
            Le pourcentage correspond à la proportion d’électeurs venus voter à l’heure indiquée.
        </h6>
    </div>

    <div id="content-side">
    <div id="map"></div>
    <!--<div class="example example-numbers">
        <div class="demo">
          <input class="typeahead" type="text" placeholder="numbers (1-10)">
        </div>
      </div>
    -->

    <!--<script src="http://d3js.org/d3.v3.min.js"></script>-->
    <script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
    <!--<script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.0.0/leaflet-omnivore.min.js'></script>-->
    <script src="js/simple_statistics.js"></script>
    <script src="js/chroma.min.js"></script>
    <script src="js/leaflet.ajax.js"></script>
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/typeahead.bundle.js"></script>

    <script type="text/javascript">
        // Initialisation de l'emprise initiale et de la carte
        var initialBounds = new L.latLngBounds(new L.latLng(47.18, -1.64), new L.latLng(47.295, -1.47));
        var map = L.map('map', {
            center: [47.2375, -1.555],
            zoom: 12,
            maxZoom: 16,
            minZoom: 11
        });

        // Ajout du fond de carte
        L.tileLayer('http://{s}.acetate.geoiq.com/tiles/' + 'terrain' + '/{z}/{x}/{y}' + '.png', {
            //maxZoom: 18,
            opacity: 0.4,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
        }).addTo(map);

        L.tileLayer('http://{s}.acetate.geoiq.com/tiles/' + 'acetate-labels' + '/{z}/{x}/{y}' + '.png', {
            //maxZoom: 18,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
        }).addTo(map);


        // Controle permettant d'afficher les informations au survol
        var info = L.control();

        info.onAdd = function (map) {
            this._div = L.DomUtil.create('div', 'info');
            this.update();
            return this._div;
        };

        info.update = function (props) {
            this._div.innerHTML = (props ? '<h4> Bureau ' + props.IDBURO + ' - Année 2008</h4>' +
                '<i><b>Adresse</b></i>:' + props.LIEU_SITE + ' ' + props.LIEU_NOM + '<br />' + props.LIEU_ADRES + '<br />' +
                '<i><b>Nombre de votants</b></i>: ' + props.resultats_2008_municipales_VOT2008 + '<br />' +
                '<i><b>Nombre d\'inscrits</b></i>: ' + props.resultats_2008_municipales_INS2008 + ' '  + '<br />' +
                '<i><b>Pourcentage de votants</b></i>: ' + props.resultats_2008_municipales_VOT2008P + '%'
                : 'Survolez un bureau de vote');
        };

        info.addTo(map);

        var myJson = {};

        function returnCoupletInfo(d) {
            for (var i = 0; i < myJson.couples.length - 1; i++) {
                var couplet = myJson.couples.slice(i, i + 2);
                var returnValue = {couplet : couplet};
                if (i < (myJson.couples.length - 2)) {
                    if (d >= couplet[0] && d < couplet[1]){
                        returnValue["position"] = i;
                        return returnValue;
                    }
                } else {
                    couplet = myJson.couples.slice(i, i + 1);
                        returnValue["position"] = i;
                        return returnValue;
                }
            }
        };

        // get color depending on population density value
        function getColor(d) {
            var valuePosition = returnCoupletInfo(d);
            var colorRanges = chroma.brewer.OrRd.slice(0,7);
            var colorRanges = chroma.brewer.Reds.slice(0,7);

            var retour = colorRanges[valuePosition.position];
            return retour;
        }
        function style(feature) {
            return {
                weight: 1,
                opacity: 1,
                color: 'white',
                //dashArray: '3',
                fillOpacity: 0.9,
                fillColor: getColor(feature.properties.resultats_2008_municipales_VOT2008P)
            };
        }

        var geojson;

        function highlightFeature(e) {
            var layer = e.target;

            layer.setStyle({
                weight: 2,
                color: '#666',
                dashArray: '',
                fillOpacity: 0.7
            });

            if (!L.Browser.ie && !L.Browser.opera) {
                layer.bringToFront();
            }

            info.update(layer.feature.properties);
        }

        function resetHighlight(e) {
            geojson.resetStyle(e.target);
            info.update();
        }

        function zoomToFeature(e) {
            var center = e.target.getBounds()
                           .getCenter();
            map.setView(center, 14);
            //map.fitBounds(e.target.getBounds());
        }

        function onEachFeature(feature, layer) {
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,
                click: zoomToFeature
            });
        }

        // Chargement de la couche GeoJSON
        L.Util.ajax("data/geo/bureaux-votes-nantes-2008.geojson").then(function(data){
            var dataPourcents = data.features.map(function(element, index, array) {
                return element.properties.resultats_2008_municipales_VOT2008P;
            });
            var dataPourcents = data.features.map(function(element, index, array) {
                return element.properties.resultats_2008_municipales_VOT2008P;
            });

            myJson.couples = ss.jenks(dataPourcents, 7);

            geojson = L.geoJson(data, {
                style: style,
                onEachFeature: onEachFeature
            });

            map.addLayer(geojson);

            addLegend();

        });

        //Ajout des crédits
        map.attributionControl.addAttribution('Données <a href="http://data.nantes.fr/">Ville de Nantes</a>, <a href="http://data.nantes.fr/licence/">licence ODBL</a> &copy;');

        function addLegend() {
            // Gestion de la légende
            var legend = L.control({position: 'bottomright'});

            legend.onAdd = function (map) {

                var div = L.DomUtil.create('div', 'info legend'),
                    grades = myJson.couples,
                    labels = [],
                    from, to;

                for (var j = 0; j < grades.length - 1; j++) {
                    from = grades[j];
                    to = grades[j + 1];

                    labels.push(
                        '<i style="background:' + getColor(from) + '"></i> ' +
                        from + (to ? '&ndash;' + to : '+'));
                }

                div.innerHTML = labels.join('<br>');
                return div;
            };

            legend.addTo(map);
        }
/*
        // instantiate the bloodhound suggestion engine
        var numbers = new Bloodhound({
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.num);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: [{
                num: 'one',
                name: 1
            }, {
                num: 'two',
                name: 2
            }, {
                num: 'three',
                name: 3
            }, {
                num: 'four',
                name: 4
            }, {
                num: 'five',
                name: 5
            }, {
                num: 'six',
                name: 6
            }, {
                num: 'seven',
                name: 7
            }, {
                num: 'eight',
                name: 8
            }, {
                num: 'nine',
                name: 9
            }, {
                num: 'ten',
                name: 10
            }]
        });

        // initialize the bloodhound suggestion engine
        numbers.initialize();

        // instantiate the typeahead UI
        $('.example-numbers .typeahead').typeahead(null, {
            displayKey: 'num',
            source: numbers.ttAdapter()
        }).on('typeahead:opened', onOpened)
            .on('typeahead:selected', onAutocompleted)
            .on('typeahead:autocompleted', onSelected);

        function onOpened($e) {
            console.log('opened');
        }

        function onAutocompleted($e, datum) {
            console.log('autocompleted');
            console.log(datum);
        }

        function onSelected($e, datum) {
            console.log('selected');
            console.log(datum);
        }
*/
    </script>




    </div>