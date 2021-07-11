import axios from 'axios'
import Swal from 'sweetalert2'

const baseURL = 'http://localhost:8000/'

export const apiHelper = axios.create({
  baseURL
})

export const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
})