import AppForm from '../app-components/Form/AppForm';

Vue.component('contact-form', {
    mixins: [AppForm],
    props: [
        'titles',
        'sources',
        'availableCategories'
    ],
    data: function() {
        return {
            form: {
                firstname:  '' ,
                lastname:  '' ,
                email:  '' ,
                prefered_language:  '' ,
                newsletter:  false ,
                title:  '' ,
                source: '',
                categories: ''
            }
        }
    }

});