import axios from 'axios'
import qs from 'qs'

axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'
let instance = axios.create()

export default {
  get (url, data = {}) {
    return instance.get(url, {
      params: data
    })
  },
  post (url, data = {}) {
    return instance.post(url, qs.stringify(data))
  },
  put (url, data = {}) {
    return instance.put(url, qs.stringify(data))
  }
}
