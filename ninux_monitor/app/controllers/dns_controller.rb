require 'resolv'
class DnsController < ApplicationController
  @@dns = Resolv::DNS.new(:nameserver => ['10.11.12.13'],
                 :search => ['fi.nnx'],
                 :ndots => 1)

  def query

    @res = Resolv.new([@@dns])

    @domain = params[:name]
    reply = @res.getaddress(@domain)
    logger.debug(reply)
    respond_to do |format|
      format.json { render json: { name: @domain, address: reply} }
    end
  end

  def reverse
    @res = Resolv.new([@@dns])
    @address = params[:address]
    name = @res.getname(@address)
    respond_to do |format|
      format.json { render json: { name: name, address: @address} }
    end
  end
end
