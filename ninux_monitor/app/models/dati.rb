class Dati < ActiveRecord::Base
  self.primary_key = 'ID'
  self.table_name = 'dati'

  def quando
    "#{self.giorno} #{self.mese} #{self.anno} - #{self.ora_remota}"
  end
end
