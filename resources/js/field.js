import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-textareafield', IndexField)
  app.component('detail-textareafield', DetailField)
  app.component('form-textareafield', FormField)
})
