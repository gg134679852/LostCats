import axios from 'axios'
import Swal from 'sweetalert2'

// const baseURL = 'https://serene-oasis-02398.herokuapp.com/'

const baseURL = 'http://127.0.0.1:8000/'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
export const axiosHelper = axios.create({
  baseURL,
  headers: { withCredentials: true }
})

export const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
})
