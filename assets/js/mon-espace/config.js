export function readConfig() {
    return {
        urlRegions:              $('#app-config').data('url-region-data'),
        urlDepartements:         $('#app-config').data('url-departement-data'),
        urlVilles:               $('#app-config').data('url-ville-data'),

        urlRegionNew:            $('#app-config').data('url-region-new'),
        urlDepartementNew:       $('#app-config').data('url-departement-new'),
        urlVilleNew:             $('#app-config').data('url-ville-new'),

        urlRegionEdit:           $('#app-config').data('url-region-edit'),
        urlDepartementEdit:      $('#app-config').data('url-departement-edit'),
        urlVilleEdit:            $('#app-config').data('url-ville-edit'),

        urlRegionDelete:         $('#app-config').data('url-region-delete'),
        urlDepartementDelete:    $('#app-config').data('url-departement-delete'),
        urlVilleDelete:          $('#app-config').data('url-ville-delete'),

        urlRegionDependances:    $('#app-config').data('url-region-dependances'),
        urlDepartementDependances: $('#app-config').data('url-departement-dependances'),
    };
}
