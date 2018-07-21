Feature: Artist feature
  Scenario: Get artists, simplest thing around
    When I add "Content-Type" header equal to "application/vnd.api+json"
    And I send a "GET" request to "/artists"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the JSON should be equal to:
    """
      {
          "data": [
              {
                  "type": "artist",
                  "id": "1",
                  "attributes": {
                      "name": "Bob Marley",
                      "slug": "bob-marley",
                      "bio": "Bob is a <b>reggae<\/b> legend",
                      "imageName": "https:\/\/s3.eu-west-3.amazonaws.com\/promoteapi\/5b51eaf94c118451747586.jpg"
                  },
                  "links": {
                      "self": "\/artist\/1"
                  }
              },
              {
                  "type": "artist",
                  "id": "2",
                  "attributes": {
                      "name": "Peter Tosh",
                      "slug": "peter-tosh",
                      "bio": "Tosh is the bush doctor !"
                  },
                  "links": {
                      "self": "\/artist\/2"
                  }
              },
              {
                  "type": "artist",
                  "id": "3",
                  "attributes": {
                      "name": "Daft Punk",
                      "slug": "daftpunk",
                      "bio": "The robot musicians"
                  },
                  "links": {
                      "self": "\/artist\/3"
                  }
              },
              {
                  "type": "artist",
                  "id": "4",
                  "attributes": {
                      "name": "Maitre Gims",
                      "slug": "maitregims",
                      "bio": "Aka Gandhi Djuna de Kinshasa"
                  },
                  "links": {
                      "self": "\/artist\/4"
                  }
              }
          ],
          "links": {
              "self": "\/artists?page=1&limit=20",
              "first": "\/artists?page=1&limit=20",
              "next": "\/artists?page=1&limit=20",
              "last": "\/artists?page=1&limit=20"
          }
      }
    """

  Scenario: Get artists, with different pagination, inclusion & filtering
    When I add "Content-Type" header equal to "application/vnd.api+json"
    And I send a "GET" request to "/artists?limit=1&page=2&sort=-name&filter[id]=3&filteroperator[id]=>=&include=gigs,labels"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the JSON should be equal to:
    """
      {
          "data": [
              {
                  "type": "artist",
                  "id": "3",
                  "attributes": {
                      "name": "Daft Punk",
                      "slug": "daftpunk",
                      "bio": "The robot musicians"
                  },
                  "links": {
                      "self": "\/artist\/3"
                  },
                  "relationships": {
                      "gigs": {
                          "links": {
                              "self": "\/artist\/3\/relationships\/gigs",
                              "related": "\/artist\/3\/gigs"
                          },
                          "data": [
                              {
                                  "type": "gig",
                                  "id": "2"
                              }
                          ]
                      },
                      "labels": {
                          "links": {
                              "self": "\/artist\/3\/relationships\/labels",
                              "related": "\/artist\/3\/labels"
                          },
                          "data": [
                              {
                                  "type": "label",
                                  "id": "3"
                              }
                          ]
                      }
                  }
              }
          ],
          "included": [
              {
                  "type": "gig",
                  "id": "2",
                  "attributes": {
                      "name": "Alive 2007",
                      "start_date": "2007-03-05T21:30:00+0000",
                      "end_date": "2007-03-05T23:30:00+0000",
                      "venue": "Bercy Arena",
                      "address": "Quai de Bercy, Paris",
                      "facebook_link": "https:\/\/www.facebook.com\/events\/981661548572560\/"
                  },
                  "links": {
                      "self": "\/gig\/2"
                  }
              },
              {
                  "type": "label",
                  "id": "3",
                  "attributes": {
                      "name": "Ninja Tune",
                      "slug": "ninja-tune",
                      "description": "Black hooded sounds"
                  },
                  "links": {
                      "self": "\/label\/3"
                  }
              }
          ],
          "links": {
              "self": "\/artists?sort=-name&page=2&limit=1",
              "first": "\/artists?sort=-name&page=1&limit=1",
              "next": "\/artists?sort=-name&page=2&limit=1",
              "last": "\/artists?sort=-name&page=2&limit=1"
          }
      }
    """