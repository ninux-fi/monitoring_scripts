FROM ruby:2.2-onbuild

RUN apt-get update && apt-get install libmysqlclient-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
CMD ["./graffitaro.rb"]
