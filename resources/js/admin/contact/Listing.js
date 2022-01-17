import AppListing from '../app-components/Listing/AppListing';

Vue.component('contact-listing', {
    mixins: [AppListing],

    props: {
        stepCount: {
            type: Number,
            default: function _default() {
                return 3;
            }
        }
    },

    data() {
        return {
            showTitlesFilter: false,
            titlesMultiselect: {},
            sourcesMultiselect: {},

            filters: {
                titles: [],
                sources: [],
            },

            currentStep: 1,
            file: null,
            importedFile: null,
            onlyMissing: true,

            numberOfSuccessfullyImportedContacts: 0,
            numberOfSuccessfullyUpdatedContacts: 0,
            numberOfFoundContacts: 0,
            numberOfContactsToReview: 0,
            contactsToImport: null,
            contacts: {}
        }
    },

    watch: {
        showTitlesFilter: function (newVal, oldVal) {
            this.titlesMultiselect = [];
        },
        titlesMultiselect: function(newVal, oldVal) {
            this.filters.titles = newVal.map(function(object) { return object['key']; });
            this.filter('titles', this.filters.titles);
        },
        showSourcesFilter: function (newVal, oldVal) {
            this.sourcesMultiselect = [];
        },
        sourcesMultiselect: function(newVal, oldVal) {
            this.filters.sources = newVal.map(function(object) { return object['key']; });
            this.filter('sources', this.filters.sources);
        }
    },

    computed: {
        lastStep: function lastStep() {
            return this.currentStep === this.stepCount;
        }
    },

    methods: {
        showImport: function showImport() {
            this.$modal.show('import-contact');
        },
        nextStep: function nextStep() {
            var _thisLocal = this;            
            
            if (this.currentStep === 1) {
                return this.$validator.validateAll().then(function (result) {
                    if (!result) {
                        _thisLocal.$notify({ type: 'error', title: 'Error!', text: 'The form contains invalid fields.' });
                        return false;
                    }
                    
                    var url = '/admin/contacts/import';
                    var formData = new FormData();

                    formData.append('fileImport', _thisLocal.file);
                    formData.append('onlyMissing', _thisLocal.onlyMissing);

                    axios.post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {
                        if (response.data.hasOwnProperty('numberOfImportedContacts') && response.data.hasOwnProperty('numberOfUpdatedContacts')) {
                            _thisLocal.currentStep = 3;
                            _thisLocal.numberOfSuccessfullyImportedContacts = response.data.numberOfImportedContacts;
                            _thisLocal.numberOfSuccessfullyUpdatedContacts = response.data.numberOfUpdatedContacts;
                            _thisLocal.loadData();
                        } else {
                            _thisLocal.currentStep = 2;
                            _thisLocal.numberOfFoundContacts = Object.keys(response.data).length;
                            _thisLocal.contactsToImport = response.data;
                            for (var i = 0; i < _thisLocal.contactsToImport.length; i++) {
                                if (_this3.contactsToImport[i].hasOwnProperty('has_conflict')) {
                                    if (_thisLocal.contactsToImport[i].has_conflict) {
                                        _thisLocal.numberOfContactsToReview++;
                                    }
                                }
                            }
                        }
                    }, function(error) {
                        if (error.response.data === "Wrong syntax in your import") _thisLocal.$notify({ type: 'error', title: 'Error!', text: 'Wrong syntax in your import.' });
                        else if (error.response.data === "Unsupported file type") _thisLocal.$notify({ type: 'error', title: 'Error!', text: 'Unsupported file type.' });
                        else _thisLocal.$notify({ type: 'error', title: 'Error!', text: 'Bah... An error has occured.' });
                    });
                });

            } else if (this.currentStep === 2) {
                _thisLocal.currentStep = 3;
                _thisLocal.loadData();
            }

            
        },
        previousStep: function previousStep() {
            this.currentStep--;
        },
        handleImportFileUpload: function handleImportFileUpload(e) {
            this.file = this.$refs.file.files[0];
            this.importedFile = e.target.files[0];
        },
        onCloseImportModal: function onCloseImportModal() {
            this.currentStep = 1;
            this.importedFile = '';
            this.onlyMissing = true;
            this.contactsToImport = null;        
        }
    }
});