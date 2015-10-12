class Nodi < ActiveRecord::Base
  self.primary_key = 'ID'
  self.table_name = 'nodi'

  has_many :dati, class_name: 'Dati', foreign_key: 'id_nodo', primary_key: "ID"

end
