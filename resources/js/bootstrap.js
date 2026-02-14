import axios from 'axios';
import toastr from 'toastr';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = 'http://127.0.0.1:8000';

// Configure toastr defaults
toastr.options = {
  closeButton: true,
  debug: false,
  newestOnTop: true,
  progressBar: true,
  positionClass: 'toast-top-right',
  preventDuplicates: false,
  onclick: null,
  showDuration: '300',
  hideDuration: '1000',
  timeOut: '3000',
  extendedTimeOut: '1000',
  hideEasing: 'linear',
  hideMethod: 'fadeOut',
};
