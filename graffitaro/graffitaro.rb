#!/bin/env ruby
require 'socket'
require 'stringio'
require 'diplomat'
require 'mysql2'

if ENV['CONSUL_URL']
  Diplomat.configure do |config|
    config.url = ENV['CONSUL_URL']
  end
end

QUERY=<<EOS
SELECT
    d.ID,
    concat_ws(".", "ninux_ping", n.nome, concat_ws("-", replace(ip_sorg, ".", "_"), replace(ip_dest, ".", "_"))) as metric,
    min,
    avg,
    max,
    unix_timestamp(STR_TO_DATE(CONCAT_WS(' ', data_remota, ora_remota),
            '%Y-%m-%d %H:%i')) as ts
FROM
    net_ping.dati as d
    JOIN net_ping.nodi n ON d.id_nodo = n.ID
WHERE
    data_remota AND ora_remota AND d.ID > ?
ORDER BY d.ID
EOS

PREFIX = ENV['previx'] || ''

def send_metrics(host, port, metrics)
  TCPSocket.open(host, port) { |s| s << metrics }
  $stderr << "Sent #{metrics.split("\n").length} lines\n"
end

def parse_and_send(input, host, port)
  last_id = 0
  cnt = 0
  buffer = StringIO.new
  input.each do |row|
    cnt += 1
    last_id = row['ID']
    root = PREFIX + row['metric']
    min = row['min']
    avg = row['avg']
    max = row['max']
    ts = row['ts']
    buffer << "#{root}.min #{min} #{ts}\n#{root}.avg #{avg} #{ts}\n#{root}.max #{max} #{ts}\n"
    if cnt % 20 == 0
      send_metrics(host, port, buffer.string)
      buffer = StringIO.new
    end
  end

  leftovers = buffer.string
  send_metrics(host, port, leftovers) unless leftovers.empty?

  last_id
end


def main()
  net_ping_service = Diplomat::Service.get('net_ping_db')
  carbon_service = Diplomat::Service.get('carbon')
  username = Diplomat::Kv.get('ninux/monitoring/net_ping/db_user')
  password = Diplomat::Kv.get('ninux/monitoring/net_ping/db_password')
  database = Diplomat::Kv.get('ninux/monitoring/net_ping/db_name')

  last_id =  Diplomat::Kv.get('ninux/monitoring/net_ping/last_id') rescue 0

  client = Mysql2::Client.new(host: net_ping_service.Address,
    port: net_ping_service.ServicePort,
    username: username, password: password, database: database)

  stmt = client.prepare(QUERY)
  result = stmt.execute(last_id)
  last_id = parse_and_send(result, carbon_service.Address, carbon_service.ServicePort)

  Diplomat::Kv.put('ninux/monitoring/net_ping/last_id', last_id.to_s)
end


main
