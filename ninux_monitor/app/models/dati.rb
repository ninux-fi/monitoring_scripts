class Dati < ActiveRecord::Base
  self.primary_key = 'ID'
  self.table_name = 'dati'

  belongs_to :nodo, class_name: 'Nodi', foreign_key: 'id_nodo', primary_key: "ID"

  def quando
    "#{self.data_remota} - #{self.ora_remota}"
  end
end
